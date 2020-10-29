<div class="container">
    <h3><?php echo $pageTitle; ?></h3>
</div>
<?php
if (isset($notification)) {
    echo "<p id=\"notification\" class=\"container\"><i class=\"fas fa-exclamation-triangle\"></i> $notification</p>";

}
?>
<!-- Displaying category's questions -->
<?php for ($i = 0; $i < $nbQuestions; $i++) { ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row card-deco">
                    <!-- Here we are searching the author's firstname to display it -->
                    <span class="col-6 font-weight-bold">
                        <?php echo $questions[$i]->author()->html_login() . ' asks:' ?>
                    </span>

                    <!-- Searching the question's category to display as well-->
                    <?php if ($questions[$i]->category() != null) { ?>
                        <span class="col-6 font-weight-bold categoryQuestions pagination justify-content-end">
                            <?php echo $questions[$i]->category()->name(); ?>
                        </span>
                    <?php } ?>
                </div>

                <!-- Displaying the title of the question-->
                <a href="index.php?action=question&id=<?php echo $questions[$i]->questionId(); ?>"
                   class="list-group-item">
                    <?php echo $questions[$i]->html_title() ?>
                </a>

                <!-- Displaying the question's publication date -->
                <span class="card-deco pagination justify-content-end">
                    <?php echo $questions[$i]->publicationDate() ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
