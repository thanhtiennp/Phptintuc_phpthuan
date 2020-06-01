<!DOCTYPE html>
<html lang="en">
<html>

<head>
    <title>Ví dụ phân trang trong PHP và MySQL</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
</head>

<body>
    <?php 
    // PHẦN XỬ LÝ PHP

    // BƯỚC 1: KẾT NỐI CSDL
    $conn = mysqli_connect('localhost', 'root', '', 'quanlywebdulich');

    // BƯỚC 2: TÌM TỔNG SỐ RECORDS
    $result = mysqli_query($conn, 'select count(id) as total from quanlyuser');
    $row = mysqli_fetch_assoc($result);
    $total_records = $row['total'];

    // BƯỚC 3: TÌM LIMIT VÀ CURRENT_PAGE
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 2;

    // BƯỚC 4: TÍNH TOÁN TOTAL_PAGE VÀ START
    // tổng số trang
    $total_page = ceil($total_records / $limit);

    // Giới hạn current_page trong khoảng 1 đến total_page
    if ($current_page > $total_page) {
        $current_page = $total_page;
    } else if ($current_page < 1) {
        $current_page = 1;
    }

    // Tìm Start
    $start = ($current_page - 1) * $limit;

    // BƯỚC 5: TRUY VẤN LẤY DANH SÁCH TIN TỨC
    // Có limit và start rồi thì truy vấn CSDL lấy danh sách tin tức
    $result = mysqli_query($conn, "SELECT * FROM quanlyuser LIMIT $start, $limit");
    ?>
    <div>
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Tài khoản</th>
                    <th>Mật khẩu</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Quyền</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <?php 
            // BƯỚC 6: HIỂN THỊ DANH SÁCH TIN TỨC

            while ($row = mysqli_fetch_assoc($result)) {
                ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["username"]; ?></td>
                <td><?php echo $row["password"]; ?></td>
                <td><?php echo $row["hoten"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
            </tr>
            <?php

        }
        ?>
        </table>
    </div>
    <div class="pagination">
        <?php 
        // PHẦN HIỂN THỊ PHÂN TRANG
        // BƯỚC 7: HIỂN THỊ PHÂN TRANG

        // nếu current_page > 1 và total_page > 1 mới hiển thị nút prev
        if ($current_page > 1 && $total_page > 1) {
            echo '<a href="test.php?page=' . ($current_page - 1) . '">Prev</a> | ';
        }

        // Lặp khoảng giữa
        for ($i = 1; $i <= $total_page; $i++) {
            // Nếu là trang hiện tại thì hiển thị thẻ span MỤC ĐÍCH : không cho ngdung click vào trang đang hiển thị
            // ngược lại hiển thị thẻ a
            if ($i == $current_page) {
                echo '<span>' . $i . '</span> | ';
            } else {
                echo '<a href="test.php?page=' . $i . '">' . $i . '</a> | ';
            }
        }

        // nếu current_page < $total_page và total_page > 1 mới hiển thị nút prev
        if ($current_page < $total_page && $total_page > 1) {
            echo '<a href="test.php?page=' . ($current_page + 1) . '">Next</a> | ';
        }
        ?>
    </div>
</body>

</html> 