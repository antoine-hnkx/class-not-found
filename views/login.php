<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Login Form</h3>
            <form action="index.php?action=login" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Username" name="login"/>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Your Password" name="password"/>
                </div>
                <?php if(isset($notification)){ ?>
                    <p id="notification"> <?php echo $notification; ?></p>
                <?php } ?>
                <div class="form-group">
                    <button class="btn btn-dark" type="submit" name="form_login">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>