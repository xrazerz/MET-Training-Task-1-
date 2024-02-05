<?php
session_start();

// connect DB
include 'db_connection.php';
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
/**
 * Start Create Functions Of CRUD
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["create"])) {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $price = $_POST["price"];
        $sql = "INSERT INTO products (id, name, description, price) VALUES ('$id', '$name', '$description', '$price')";
        if (mysqli_query($con, $sql)) {
            header('Location: product.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }

    // Edit
    if (isset($_POST["edit"])) {
        // Catch Data By id Parameters
        $edit_id = $_POST["edit_id"];
        $edit_sql = "SELECT * FROM products WHERE id='$edit_id'";
        $edit_result = mysqli_query($con, $edit_sql);
        $edit_row = mysqli_fetch_assoc($edit_result);
        $edit_id = $edit_row["id"];
        $edit_name = $edit_row["name"];
        $edit_description = $edit_row["description"];
        $edit_price = $edit_row["price"];
    }

    // Delete
    if (isset($_POST["delete"])) {
        $delete_id = $_POST["delete_id"];
        $delete_sql = "DELETE FROM products WHERE id='$delete_id'";
        if (mysqli_query($con, $delete_sql)) {
            header('Location: product.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}

if (isset($_POST["search_btn"])) {
    $search_query = $_POST["search"];
    $sql = "SELECT * FROM products WHERE name LIKE '%$search_query%'";
    $result = mysqli_query($con, $sql);
}

// Filter
if (isset($_POST["filter_btn"])) {
    $filter_option = $_POST["filter_option"];
    switch ($filter_option) {
        case "price_asc":
            $sql = "SELECT * FROM products ORDER BY price ASC";
            $result = mysqli_query($con, $sql);
            break;
        case "price_desc":
            $sql = "SELECT * FROM products ORDER BY price DESC";
            $result = mysqli_query($con, $sql);
            break;
    }
}

// Get All Data
$sql = "SELECT * FROM products";
$result = mysqli_query($con, $sql);
// Check Query
if (!$result) {
    echo "Error fetching products: " . mysqli_error($con);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنتجات</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="home.php">LOGO</a>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="logout.php">LOGOUT</a></button>
    </form>
  </div>
</nav>
    <div class="container mt-5">
        <h1 class="mb-4">Product Management</h1>

        <!-- Product Form -->
        <form method="post" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <input type="text" class="form-control" name="id" placeholder="Product ID" value="<?php echo isset($edit_id) ? $edit_id : ''; ?>">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" class="form-control" name="name" placeholder="Product Name" value="<?php echo isset($edit_name) ? $edit_name : ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <input type="text" class="form-control" name="description" placeholder="Product Description" value="<?php echo isset($edit_description) ? $edit_description : ''; ?>">
                </div>
                <div class="form-group col-md-2">
                    <input type="number" class="form-control" name="price" placeholder="Product Price" value="<?php echo isset($edit_price) ? $edit_price : ''; ?>">
                </div>
                <div class="form-group col-md-1 d-inline">
                    <?php if (isset($edit_id)) { ?>
                        <button type="submit" class="btn btn-primary btn-block" name="update">Update</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-success btn-block" name="create">Add</button>
                    <?php } ?>
                </div>
            </div>
        </form>

        <!-- Search and Filter -->
        <form method="post" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="text" class="form-control" name="search" placeholder="Search by Product Name">
                </div>
                <div class="form-group col-md-4">
                    <select name="filter_option" class="form-control">
                        <option value="">-- Select Filter Option --</option>
                        <option value="price_asc">Price Low to High</option>
                        <option value="price_desc">Price High to Low</option>
                        <!-- Add more filter options as needed -->
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary btn-block" name="search_btn">Search & Filter</button>
                </div>
            </div>
        </form>

        <!-- Display Products -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["name"]; ?></td>
                            <td><?php echo $row["description"]; ?></td>
                            <td><?php echo $row["price"]; ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form method="post">
                                        <input type="hidden" name="edit_id" value="<?php echo $row["id"]; ?>">
                                        <button type="submit" class="btn btn-warning btn-sm" name="edit">Edit</button>
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="delete_id" value="<?php echo $row["id"]; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" name="delete">Delete</button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
// Close database connection
mysqli_close($con);
?>