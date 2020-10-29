<div class="container">
    <h3><?php echo $pageTitle ?></h3>
</div>
<!-- Edit form -->
<form action="index.php?action=editQuestion" method="post">
    <div class="container">
        <!-- The title-->
        <div class="form-group">
            <input placeholder="Your title..." type="text" class="form-control" maxlength="80" name="question_title"
                   value="<?php echo $question->html_title(); ?>"/>
        </div>
        <!-- The Subject-->
        <div class="form-group">
            <textarea placeholder="Your subject..." class="form-control" name="question_subject"
                      rows="5"><?php echo $question->html_subject(); ?></textarea>
        </div>
        <!-- Displaying all categories -->
        <div class="form-group">
            <select class="browser-default custom-select custom-select-md mb-3" name="question_category_id">
                <?php for ($i = 0; $i < count($categories); $i++) { ?>
                    <option <?php if ($question->category() != null && $categories[$i]->id() == $question->category()->id()) echo "selected"; ?> value="<?php echo $categories[$i]->id(); ?>"><?php echo $categories[$i]->html_name(); ?></option>
                <?php } ?>
            </select>
        </div>
        <?php if (isset($notification)) { ?>
            <p id="notification"> <?php echo $notification; ?></p>
        <?php } ?>
        <?php if(isset($questionId)) { ?>
            <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">
            <input class="btn btn-dark" type="submit" name="form_edit" value="Edit question">
        <?php } else { ?>
            <input class="btn btn-dark" type="submit" name="form_question" value="Post question">
        <?php } ?>
    </div>
</form>