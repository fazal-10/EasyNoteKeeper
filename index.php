<?php
//connecting to database
require_once "config.php";
$conn = mysqli_connect($servername, $username, $password, $database);

//Die if connection was not succesful
if(!$conn)
{
    die("Sorry,we failed to connect : " . mysqli_connect_error());
}
?>
<?php
//INSERT INTO `notes` (`sr no`, `title`, `description`, `tstamp`) VALUES (NULL, 'buy books', 'buy 5 books from store.', current_timestamp());
$insert = false;
$update = false;
$delete = false;

if(isset($_GET['delete']))
{
    $sno = $_GET['delete'];
    $delete = true;
    $sql = "DELETE FROM `notes` WHERE `sr no` = '$sno'";
    $result = mysqli_query($conn,$sql);
}
if ($_SERVER['REQUEST_METHOD']=='POST')
{
    if(isset($_POST['snoEdit']))
    {
        $sno = $_POST["snoEdit"];
        $title = $_POST["titleEdit"];
        $description = $_POST["descriptionEdit"];
        //update the record
        $sql = "UPDATE `notes` SET `title` = '$title', `description`='$description' WHERE `notes`.`sr no` = $sno";
        $result = mysqli_query($conn,$sql);
        if($result)
        {
            $update = true;
        }
        else
        {
            echo "fail";
        }
    }
    else
    {
        //variables to be inserted in table
        $title = $_POST["title"];
        $description = $_POST["description"];

        //sql squery to be executed
        $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
        $result = mysqli_query($conn,$sql);

        //inserting data
        if($result)
        {
            $insert = true;
        }
        else
        {
            echo "<br>The record was not inserted successfully because of this error --> " . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyNoteKeeper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="shortcut icon" href="./logo.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <style>
        body{
            background-color:#e8f4f4;
        }
        .title{
            color: black !important;
            font-size: 1.3rem;
        }
        .delete {
            margin-left: 10px;
        }
        @media screen and (max-width: 769px) {
            .modal-dialog {
                max-width: 90%; /* Reduce the width of the modal for smaller screens */
            }

            .modal-footer {
                text-align: center; /* Center the buttons in the modal footer */
            }

            #sno{
                width:46px
            }

            .btns{
                display: flex;
            }
        }
        @media screen and (max-width: 475px) {
            .container{
                width:363px;
                margin:auto;
            }
        }
    </style>
</head>

<body>
    <!-- Edit modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
        Edit Modal
    </button> -->

    <!--Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/CRUD/index.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="title" class="form-label">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Note Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-block mr-auto">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="/CRUD/logo.png" height="50 px" alt="notes logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active title" aria-current="page" href="#">EasyNoteKeeper</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php

    if($insert)
    {
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success</strong> Your note has been inserted successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }

    ?>
    <?php

    if($update)
    {
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success</strong> Your note has been updated successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }

    ?>
    <?php

    if($delete)
    {
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success</strong> Your note has been deleted successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }

    ?>
    
    <div class="container my-4">
        <h2>Add a Note</h2>
        <form action="/CRUD/index.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Note Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <div class="container my-5">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col" id="sno">Sr No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($conn,$sql);
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno++;
          echo "<tr>
          <th scope='row'>" . $sno . "</th>
          <td>" . $row['title'] . "</td>
          <td>" . $row['description'] . "</td>
          <td class='btns'><button class='edit btn btn-sm btn-primary' id=".$row['sr no'].">Edit</button><button class='delete btn btn-sm btn-primary' id=d".$row['sr no'].">Delete</button></td>
          </tr>"; 
      }
        ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous">
    </script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
    </script>
    <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
            // console.log("edit ", );
            tr = e.target.parentNode.parentNode;
            title = tr.getElementsByTagName("td")[0].innerText;
            description = tr.getElementsByTagName("td")[1].innerText;
            console.log(title, description);
            titleEdit.value = title;
            descriptionEdit.value = description;
            snoEdit.value = e.target.id;
            console.log(e.target.id);
            $('#editModal').modal('toggle');
        })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
        element.addEventListener("click", (e) => {
            // console.log("delete ", );
            sno = e.target.id.substr(1, );
            if (confirm("Are you sure you want to delete this note")) {
                console.log("yes");
                window.location = `/CRUD/index.php?delete=${sno}`;
            } else {
                console.log("no");
            }
        })
    })
    </script>
</body>

</html>