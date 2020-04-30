<?php include('functions.php'); ?>
<?php include('includes/head.php'); ?>

    <div class="login">

        <form id="loginform" method="post" autocomplete="off">
            <div class="notify"><?php echo $errors; ?></div>
            <input type="text" class="login_username" name="username" placeholder="Username" autocomplete="false">
            <input type="password" class="login_password" name="password" placeholder="Password" autocomplete="false">
            <input type="submit" name="submit" value="Submit">
        </form>

    </div>

<?php include('includes/footer.php'); ?>