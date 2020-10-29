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
                    <?php // TODO EXAM $question->author()->html_login() ?>
                    <p class="card-login font-weight-bold"><?php echo $question->author()->login() . ' asks:'; ?></p>
                </div>
                <div class="container col-4 pagination justify-content-end" id="question-date">
                    <p class="card-login"><?php echo $question->publicationDate() ?></p>
                </div>
            </div>

        </div>

        <!-- Displaying the question's title and question's subject -->
        <div class="card-body">
            <h5 id="question-title" class="card-title"><?php echo $question->html_title();
                if ($question->state() != null) echo " [" . strtoupper($question->state()) . "]"; ?></h5>
            <p class="card-text"><?php echo Utils::html_replace_enter_by_br($question->html_subject()); ?></p>
        </div>

        <!-- Displaying all question's features -->
        <div class="question-btn card-footer">

            <div class="container">
                <div class="row">
                    <!-- New Answer Button -->
                    <form class="form-btn" action="index.php?action=newAnswer#new_answer" method="post">
                        <button class="btn btn-dark" type="submit" name="new_answer">Answer</button>
                        <input type="hidden" name="id" value="<?php echo $question->questionId() ?>">
                    </form>

                    <!-- Displaying edit button if user == question's author -->
                    <?php if (isset($memberLogin) && $memberLogin == $question->author()->login()) { ?>
                        <form class="form-btn" action="index.php?action=editQuestion" method="post">
                            <input type="hidden" name="question_id" value="<?php echo $question->questionId() ?>">
                            <button class="btn btn-dark" type="submit" name="edit">Edit</button>
                        </form>
                    <?php } ?>
                    <?php if (isset($memberLogin) && $memberLogin == $question->author()->login() && $question->bestAnswer() != null) { ?>
                        <form class="form-btn float-left"
                              action="index.php?action=question&id=<?php echo $question->questionId() ?>" method="post">
                            <div class="container card-footer-container">
                                <input type="hidden" name="answer_id"
                                       value="<?php echo $question->bestAnswer()->answerId() ?>">
                                <input type="hidden" name="question_id"
                                       value="<?php echo $question->questionId() ?>">
                                <button class="btn btn-secondary" type="submit" name="delete_best_answer">Remove Best
                                    Answer
                                </button>
                            </div>
                        </form>
                    <?php } ?>
                    <!-- Displaying admin buttons if user == admin -->
                    <?php if (isset($isAdmin)) { ?>
                        <?php if ($question->state() == 'duplicated') { ?>
                            <form class="form-btn"
                                  action="index.php?action=question&id=<?php echo $question->questionId() ?>"
                                  method="post">
                                <input type="hidden" name="question_id" value="<?php echo $question->questionId() ?>">
                                <button class="btn btn-secondary" type="submit" name="remove_duplicate">Remove
                                    Duplicate
                                </button>
                            </form>
                        <?php } else { ?>
                            <form class="form-btn"
                                  action="index.php?action=question&id=<?php echo $question->questionId() ?>"
                                  method="post">
                                <input type="hidden" name="question_id" value="<?php echo $question->questionId() ?>">
                                <button class="btn btn-secondary" type="submit" name="duplicate">Duplicate</button>
                            </form>
                        <?php } ?>
                        <form class="form-btn"
                              action="index.php?action=question&id=<?php echo $question->questionId() ?>" method="post">
                            <input type="hidden" name="question_id" value="<?php echo $question->questionId() ?>">
                            <button class="btn btn-danger" type="submit" name="delete">Delete</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Displaying notification in case of not available feature-->
<?php if (isset($notification)) { ?>
    <div class="container" id="notification"><?php echo $notification; ?> </div>
<?php } ?>

<!-- Displaying all question's answers -->
<?php for ($i = 0; $i < $nbAnswers; $i++) { ?>
    <!-- Displaying the best answer on top if there is one -->
    <?php if ($i == 0 && $question->bestAnswer() != null) { ?>
        <div class="container">
            <h3>Best Answer:</h3>
        </div>
    <?php } elseif ($i == 0) { ?>
        <div class="container">
            <h3>Answers:</h3>
        </div>
        <?php continue;
    } ?>
    <div class="container" id="<?php echo $question->answers()[$i]->answerId(); ?>">
        <div class="card">
            <!-- Displaying answer's author -->
            <div class="card-header">
                <p class="card-text card-login font-weight-bold"><?php echo $question->answers()[$i]->author()->html_login() . ' answers:' ?></p>
            </div>
            <!-- Displaying the answer -->
            <div class="card-body">
                <p class="card-text"><?php echo Utils::html_replace_enter_by_br($question->answers()[$i]->html_subject()); ?></p>
            </div>

            <!-- Displaying answer's votes -->
            <div class="row card-footer question-btn">
                <div class="col-6 likes">
                    <form class="form-btn float-left"
                          action="index.php?action=question&id=<?php echo $question->questionId() ?>" method="post">
                        <div class="container card-footer-container">
                            <input type="hidden" name="question_id" value="<?php echo $question->questionId() ?>">
                            <input type="hidden" name="answer_id"
                                   value="<?php echo $question->answers()[$i]->answerId(); ?>">
                            <input type="hidden" name="member_id"
                                   value="<?php echo $question->answers()[$i]->author()->memberId(); ?>">
                            <button class="btn btn-dark" type="submit"
                                    name="like"><i
                                        class="fas fa-thumbs-up"></i> <?php echo $question->answers()[$i]->nbLikes(); ?>
                            </button>
                            <button class="btn btn-dark"
                                    type="submit" name="dislike"><i
                                        class="fas fa-thumbs-down"></i> <?php echo $question->answers()[$i]->nbDislikes(); ?>
                            </button>
                        </div>
                    </form>
                    <!-- Displaying best answer button if user == question's author and the answer is not his-->
                    <?php if (isset($memberLogin) && $memberLogin == $question->author()->login()) { ?>
                        <?php if ($question->bestAnswer() == null || ($question->bestAnswer() != null && $question->bestAnswer()->answerId() != $question->answers()[$i]->answerId())) { ?>
                            <form class="form-btn float-left"
                                  action="index.php?action=question&id=<?php echo $question->questionId() ?>"
                                  method="post">
                                <div class="container card-footer-container">
                                    <input type="hidden" name="answer_id"
                                           value="<?php echo $question->answers()[$i]->answerId() ?>">
                                    <input type="hidden" name="question_id"
                                           value="<?php echo $question->questionId() ?>">
                                    <button class="btn btn-success" type="submit" name="best_answer"><i
                                                class="far fa-check-circle"></i></button>
                                </div>
                            </form>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="container card-footer-container col-6">
                    <span class="date card-deco pagination justify-content-end">
                        <?php echo $question->answers()[$i]->publicationDate() ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if ($i == 0) { ?>
        <div class="container">
            <h3>Answers:</h3>
        </div>
    <?php } ?>
<?php } ?>
