<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Register Form</h3>
            <form action="index.php?action=register" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your First Name" name="firstname" maxlength="25"/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Last Name" name="lastname" maxlength="25"/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Mail" name="mail" maxlength="50"/>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Username" name="login" maxlength="25"/>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Your Password" name="password" maxlength="60"/>
                    <?php if(isset($notification)){ ?>
                        <p id="notification"> <?php echo $notification; ?></p>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <button class="btn btn-dark" type="submit" name="form_register">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>