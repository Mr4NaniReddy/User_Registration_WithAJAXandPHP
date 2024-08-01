<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP AJAX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">PHP CRUD with AJAX, Validation</h2>
    <div class="text-right mb-4">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname">
                            <div class="error text-danger" id="firstnameError"></div>
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname">
                            <div class="error text-danger" id="lastnameError"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <div class="error text-danger" id="emailError"></div>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile">
                            <div class="error text-danger" id="mobileError"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="error text-danger" id="passwordError"></div>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                            <div class="error text-danger" id="confirmPasswordError"></div>
                        </div>
                        <div class="form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <input type="file" class="form-control-file" id="profile_picture" name="profile_picture" accept="image/*">
                            <div class="error text-danger" id="profile_pictureError"></div>
                            <img id="imagePreview" src="#" alt="Image Preview" style="display: none; width: 100px; height: 100px;" class="mt-3"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update User Modal -->
    <div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateUserForm" enctype="multipart/form-data">
                        <input type="hidden" id="update_hiddenid" name="update_hiddenid">
                        <div class="form-group">
                            <label for="update_firstname">First Name</label>
                            <input type="text" class="form-control" id="update_firstname" name="update_firstname">
                            <div class="error text-danger" id="update_firstnameError"></div>
                        </div>
                        <div class="form-group">
                            <label for="update_lastname">Last Name</label>
                            <input type="text" class="form-control" id="update_lastname" name="update_lastname">
                            <div class="error text-danger" id="update_lastnameError"></div>
                        </div>
                        <div class="form-group">
                            <label for="update_email">Email</label>
                            <input type="email" class="form-control" id="update_email" name="update_email">
                            <div class="error text-danger" id="update_emailError"></div>
                        </div>
                        <div class="form-group">
                            <label for="update_mobile">Mobile</label>
                            <input type="text" class="form-control" id="update_mobile" name="update_mobile">
                            <div class="error text-danger" id="update_mobileError"></div>
                        </div>
                        <div class="form-group">
                            <label for="update_password">Password</label>
                            <input type="password" class="form-control" id="update_password" name="update_password" readonly>
                        </div>
                        <div class="form-group">
                            <label for="update_profile_picture">Profile Picture</label>
                            <input type="file" class="form-control-file" id="update_profile_picture" name="update_profile_picture" accept="image/*">
                            <div class="error text-danger" id="update_profile_pictureError"></div>
                            <img id="update_imagePreview" src="#" alt="Image Preview" style="display: none; width: 100px; height: 100px;" class="mt-3"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Display Users Table -->
    <div id="displayDataTable" class="mt-4"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        displayData();

        $('#profile_picture').change(function(){
            readURL(this, '#imagePreview');
        });

        $('#update_profile_picture').change(function(){
            readURL(this, '#update_imagePreview');
        });

        $('#addUserForm').submit(function(e){
            e.preventDefault();
            clearErrors();
            var formData = new FormData(this);
            $.ajax({
                url: 'insert.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#addUserModal').modal('hide');
                    displayData();
                    $('#addUserForm')[0].reset();
                    $('#imagePreview').hide();
                    // alert("data added successfullY");
                },
                error: function(xhr){
                    var errors = JSON.parse(xhr.responseText);
                    displayErrors(errors);
                }
            });
        });

        $('#updateUserForm').submit(function(e){
            e.preventDefault();
            clearErrors();
            var formData = new FormData(this);
            $.ajax({
                url: 'update.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#updateUserModal').modal('hide');
                    displayData();
                },
                error: function(xhr){
                    var errors = JSON.parse(xhr.responseText);
                    displayErrors(errors, 'update_');
                }
            });
        });
    });

    function displayData(){
        $.ajax({
            url: 'display.php',
            type: 'GET',
            success: function(data){
                $('#displayDataTable').html(data);
            }
        });
    }

    function getUserDetails(id){
        $.ajax({
            url: 'fetch.php',
            type: 'POST',
            data: {id: id},
            success: function(data){
                var user = JSON.parse(data);
                $('#update_hiddenid').val(user.id);
                $('#update_firstname').val(user.firstname);
                $('#update_lastname').val(user.lastname);
                $('#update_email').val(user.email);
                $('#update_mobile').val(user.mobile);
                $('#update_password').val(user.password);
                if(user.profile_picture){
                    $('#update_imagePreview').attr('src', user.profile_picture).show();
                } else {
                    $('#update_imagePreview').hide();
                }
                $('#updateUserModal').modal('show');
            }
        });
    }

    function deleteUser(id){
        if(confirm('Are you sure you want to delete this user?')){
            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: {id: id},
                success: function(data){
                    displayData();
                }
            });
        }
    }

    function readURL(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(previewId).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearErrors() {
        $('.error').text('');
    }

    function displayErrors(errors, prefix = '') {
        for (var key in errors) {
            $('#' + prefix + key + 'Error').text(errors[key]);
        }
    }
</script>
</body>
</html>
