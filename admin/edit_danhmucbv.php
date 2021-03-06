<style>
    .required {
        color: red;
    }
</style>

<?php include('includes/header.php'); ?>

<div id="page-wrapper">

    <div class="container-fluid"> 

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <?php
                include('../connect/function.php');
                include('../connect/myconnect.php'); // mo ket noi

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                // kiểm tra id có phải là kiểu số và có tồn tại không
                if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
                    $id = $_GET['id'];
                } else {
                    // chuyển hướng đến trang list_danhmucbv
                    header('Location: list_danhmucbv.php');
                    /*Lưu ý: hàm header nếu ở trong file tồn tại cả tiếng việt và cả mã html thì sẽ bị lôi
                    - cách xử lý
                    Lưu tạm trong bộ nhớ đệm
                    */
                    // thoát ra để chạy tiếp các câu lệnh phía dưới
                    exit();
                }
                $message = '';
                // kiểm tra nút submit đã được nhấn chưa.
                if (isset($_POST['submit'])) {
                    // nếu đã nhấn thì vào đây
                    $errors = array();
                    // kiểm tra ô title có rỗng
                    if (empty($_POST['danhmucbaiviet'])) {
                        // vào này là rỗng nè
                        $errors[] = "danhmucbaiviet";
                    } else {
                        // ko rỗng thì vào này lấy dữ liệu ra
                        $danhmucbaiviet = $_POST['danhmucbaiviet'];
                    }
                    if (empty($_POST['ordernum'])) {
                        $ordernum = 0;
                    } else {
                        $ordernum = $_POST['ordernum'];
                    }
                    $menu = $_POST['menu'];
                    $home = $_POST['home'];
                    $status = $_POST['status'];

                    /*
                    $query_kt = "SELECT * FROM quanlydanhmuc WHERE parent_id = " . $parent_id . " ORDER BY parent_id DESC";
                    $result_kt = mysqli_query($conn, $query_kt);
                    
                    // đệ quy lặp all các thèn danh mục con

                    while ($kt = mysqli_fetch_array($result_kt, MYSQLI_ASSOC))
                    {

                    }
                    */
                    // kiểm tra mảng error có RỖNG?
                    if (empty($errors)) {
                        // rỗng rồi thì ...
                        if ($_POST['parent'] == 0) {
                            $parent_id = 0;
                        } else {

                            $parent_id = $_POST['parent'];
                        }

                        $query = "UPDATE tbldanhmucbaiviet 
                        SET danhmucbaiviet='{$danhmucbaiviet}', menu = {$menu}, home={$home},ordernum={$ordernum}, parent_id={$parent_id} WHERE id={$id}";
                        // chạy câu lệnh truy vấn
                        $result = $conn->query($query);
                        if ($result == true) {
                            echo "<h4 style='color: blue'>Chỉnh sửa thành công</h4>";
                        } else {
                            echo "Error " . $query . " Lỗi: " . $conn->error;
                        }
                        // set về cho all các ô là rỗng mỗi khi thêm xong
                        $_POST['danhmucbaiviet'] = "";
                        $_POST['ordernum'] = "";
                    } else if (!empty($errors)) {
                        echo "<p class='required' >Bạn hãy nhập đầy đủ thông tin</p>";
                    }
                }
                ?>

                <?php
                $sql_id = "SELECT danhmucbaiviet, parent_id, menu, home, ordernum, status FROM tbldanhmucbaiviet WHERE id={$id}";
                $result_id = mysqli_query($conn, $sql_id);
                // kiểm tra xem id  có tồn tại hay ko
                if (mysqli_num_rows($result_id) == 1) {
                        list($danhmucbaiviet, $parent_id, $menu, $home, $ordernum, $status) = mysqli_fetch_array($result_id, MYSQLI_NUM);
                    } else {
                    if (empty($id)) {
                            $message = "<p class='required'>ID user không tồn tại</p>";
                        }
                }
                ?>

                <form name="frmadd_video" method="POST">

                    <h3>Chỉnh sửa danh mục có ID:<?php echo $_GET['id']; ?></h3>
                    <div class="form-group">
                        <label>Danh mục bài viết</label>
                        <input type="text" name="danhmucbaiviet" class="form-control" placeholder="Danh mục bài viết" value="<?php if (isset($danhmucbaiviet)) {
                                                                                                                                    echo $danhmucbaiviet;
                                                                                                                                } ?>">
                        <?php 
                        if (isset($errors) && in_array('danhmucbaiviet', $errors)) {
                            echo "<p class='required'>Bạn chưa nhập Danh mục bài viết</p>";
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label style="display: block">Chọn danh mục</label>
                        <?php selectCtrl('parent', 'forformdim'); ?>

                    </div>

                    <div class="form-group">
                        <label style="display: block;">Hiển thị menu</label>
                        <label class="radio-inline"><input checked="checked" type="radio" name="menu" value="1">Hiển Thị</label>
                        <label class="radio-inline"><input type="radio" name="menu" value="0">Không hiển thị</label>

                    </div>

                    <div class="form-group">
                        <label style="display: block;">Hiển thị Home</label>
                        <label class="radio-inline"><input checked="checked" type="radio" name="home" value="1">Hiển Thị</label>
                        <label class="radio-inline"><input type="radio" name="home" value="0">Không hiển thị</label>

                    </div>

                    <div class="form-group">
                        <label>Thứ Tự</label>
                        <input type="number" name="ordernum" class="form-control" placeholder="ordernum" value="<?php if (isset($ordernum)) {
                                                                                                                    echo $ordernum;
                                                                                                                } ?>">
                        <?php 
                        if (isset($errors) && in_array('ordernum', $errors)) {
                            echo "<p class='required'>Bạn chưa nhập so thu tu</p>";
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label style="display: block;">Trạng Thái</label>
                        <label class="radio-inline"><input checked="checked" type="radio" name="status" value="1">Hiển Thị</label>
                        <label class="radio-inline"><input type="radio" name="status" value="0">Không hiển thị</label>

                    </div>
                    <input type="submit" name="submit" class="btn btn-primary" value="Chỉnh sửa">
                </form>


            </div>
        </div>

        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<?php include('includes/footer.php'); ?> 