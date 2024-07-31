<?php include 'inc/header.php'; ?>
<?php
 Session::checkSession();
 if (isset($_GET['q'])) {
 	$quesnumber = (int) $_GET['q'];
 }else{
 	header("Location:exam.php");
 }
 $total    = $exam->getTotalRows();
 $question = $exam->getQuestionNumber($quesnumber);

 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 	$process = $pro->getProcessData($_POST);
 }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="quizz.css" type="text/css" rel="stylesheet">
    <style>
        .button1 {
            margin: 10px;
            background-color: green;
            padding: 10px 20px;
        }
        .button2 {
            margin: 10px;
            background-color: red;
            padding: 10px 20px;
        }
        .button3 {
            margin: 10px;
            background-color: purple;
            padding: 10px 20px;
        }
        .pal {
            margin: 10px;
            padding: 10px 20px;
        }
        .timer {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
    <script>
        var timeLeft = 20; // Set the initial time in seconds
        var timerId;

        function startTimer() {
            timerId = setInterval(countdown, 1000); // Start the timer
        }

        function countdown() {
            if (timeLeft == 0) {
                clearTimeout(timerId);
                document.getElementById("questionForm").submit(); // Submit the form when time runs out
            } else {
                document.getElementById("timer").innerHTML = timeLeft + " seconds remaining";
                timeLeft--;
            }
        }

        function goToNextQuestion() {
            var currentQuestionNumber = <?php echo $quesnumber; ?>;
            var nextQuestionNumber = currentQuestionNumber + 1;
            window.location.href = 'exam.php?q=' + nextQuestionNumber; // Redirect to the next question
        }

        window.onload = function() {
            startTimer(); // Start the timer when the window loads
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="mt-5">Question <?php echo $question['quesNo']." of ". $total; ?></h1>
                <div id="timer" class="timer"></div>
                <br/>
                <br/>
            </div>

            <div class="col-lg-3"></div>

            <div class="col-lg-6">
                <form id="questionForm" method="post" action="">
                    <table>
                        <tr>
                            <td colspan="2">
                                <h3>Question <?php echo $question['quesNo']." : ".$question['ques']; ?></h3>
                            </td>
                        </tr>
                        <?php
                        $answer = $exam->getAnswer($quesnumber);
                        if ($answer) {
                            while ($result = $answer->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" name="ans" class="form-check-input" value="<?php echo $result['id']; ?>" /><?php echo $result['ans']; ?>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php } } ?>
                        <tr>
                            <td>
                                <br/>
                                <input type="submit" name="submit" class="btn btn-primary" value="Continue" />
                                <input type="hidden" name="quesnumber" value="<?php echo $quesnumber; ?>" />
                            </td>
                        </tr>
                    </table>
                </form>
                <br/>
                <br/>
                <!-- Legend Buttons -->
                <!-- <div>
                    <button class="button1">Answered</button>
                    <button class="button2">Not Answered</button>
                    <button class="button3">Answered & Marked for Review</button>
                </div> -->
            </div>

            <div class="col-lg-3"></div>
        </div>
    </div>

<?php include 'inc/footer.php'; ?> 
</body>
</html>
