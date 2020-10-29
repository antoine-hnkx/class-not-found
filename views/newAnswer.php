<!-- Question Part -->
<div class="container">
    <h3>Question:</h3>
</div>
<!-- Displaying the question -->
<div class="container">
    <div class="card">

        <!-- Displaying user's login -->
        <div class="card-header">
            <div class="row">
                <div class="container col-8">
                    <p class="card-login font-weight-bold"><?php echo $question->author()->html_login() . ' asks:'; ?></p>
                </div>
                <div class="container col-4 pagination justify-content-end">
                    <p class="card-login"><?php echo $question->publicationDate() ?></p>
                </div>
            </div>
        </div>

        <!-- Displaying the question's title and question's subject -->
        <div class="card-body">
            <h5 id="question-title" class="card-title"><?php echo $question->html_title(); ?></h5>
            <p class="card-text"><?php echo Utils::html_replace_enter_by_br($question->html_subject()); ?></p>
        </div>
    </div>
</div>

<!-- Answer Form Part -->
<div class="container" id="new_answer">
    <div class="row">
        <div class="col-md-12">
            <h3>Write your answer:</h3>
            <form action="index.php?action=newAnswer" method="post">
                <div class="form-group">
                    <textarea class="form-control" name="answer_text" rows="5" placeholder="Your Answer..."></textarea>
                    <?php if(isset($notification)){ ?>
                        <p id="notification"> <?php echo $notification; ?></p>
                    <?php } ?>
                </div>
                <div class="form-group">
                    <input class="btn btn-dark" type="submit" name="form_answer" value="Post Answer">
                    <input type="hidden" name="id" value="<?php echo $question->questionId(); ?>">
                </div>
            </form>
        </div>
    </div>
</div>