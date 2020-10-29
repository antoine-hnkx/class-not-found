            </div>
        </div>
    </div>
</section>
<!-- footer -->
<footer class="py-4 bg-dark">
    <div class="container text-white">
        <div class="row">
        <div class="col-6">
            <h5 class="m-0 text-center">Projet Web 2018-2019</h5>
            <p class="m-0 text-center">antoine.honinckx@student.vinci.be / yusuf.yilmaz@student.vinci.be</p>
        </div>
        <?php if($nbAdmins > 0) { ?>
        <div class="col-6">
            <span>Admins:</span>
            <ul>
                <?php foreach ($admins as $admin) { ?>
                <li><?php echo $admin->html_login(); ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        </div>
    </div>
</footer>

<!-- JavaScript (Bootstrap) -->
<script src="<?php echo VIEWS ?>js/jquery-3.3.1.slim.min.js"></script>
<script src="<?php echo VIEWS ?>js/bootstrap.min.js"></script>
</body>
</html>

