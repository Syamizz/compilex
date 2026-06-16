<?php
function saveProgress($conn, $user_id, $chapter, $page)
{
    $stmt = $conn->prepare("
        SELECT 
            p.prog_id,
            p.pages AS total_pages,
            COALESCE(up.current_page, 0) AS current_page
        FROM progress p
        LEFT JOIN users_progress up
            ON p.prog_id = up.prog_id
            AND up.user_id = ?
        WHERE p.chapter = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $user_id, $chapter);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$row = $result->fetch_assoc()) {
        $stmt->close();
        return false;
    }

    $prog_id = (int)$row['prog_id'];
    $totalPages = max(1, (int)$row['total_pages']);
    $savedPage = (int)$row['current_page'];

    $newPage = max($savedPage, $page);
    $percentage = round(($newPage / $totalPages) * 100, 2);
    $status = $percentage >= 100 ? 'Completed' : 'Ongoing';

    $stmt->close();

    $check = $conn->prepare("
        SELECT up_id
        FROM users_progress
        WHERE user_id = ?
        AND prog_id = ?
        LIMIT 1
    ");
    $check->bind_param("ii", $user_id, $prog_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($existing = $checkResult->fetch_assoc()) {
        $update = $conn->prepare("
            UPDATE users_progress
            SET 
                current_page = ?,
                percentage = ?,
                status = ?,
                updated_at = CURDATE()
            WHERE up_id = ?
        ");
        $update->bind_param("idss", $newPage, $percentage, $status, $existing['up_id']);
        $update->execute();
        $update->close();
    } else {
        $idResult = $conn->query("
            SELECT up_id
            FROM users_progress
            WHERE up_id LIKE 'UP%'
            ORDER BY CAST(SUBSTRING(up_id, 3) AS UNSIGNED) DESC
            LIMIT 1
        ");

        if ($idResult && $idRow = $idResult->fetch_assoc()) {
            $lastNumber = (int)substr($idRow['up_id'], 2);
            $up_id = 'UP' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $up_id = 'UP001';
        }

        $insert = $conn->prepare("
            INSERT INTO users_progress (
                up_id,
                user_id,
                prog_id,
                status,
                current_page,
                percentage,
                updated_at
            )
            VALUES (?, ?, ?, ?, ?, ?, CURDATE())
        ");
        $insert->bind_param("siisid", $up_id, $user_id, $prog_id, $status, $newPage, $percentage);
        $insert->execute();
        $insert->close();
    }

    $check->close();

    return true;
}
?>