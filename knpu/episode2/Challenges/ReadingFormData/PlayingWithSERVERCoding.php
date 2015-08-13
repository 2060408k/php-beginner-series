<?php

namespace Challenges\ReadingFormData;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class PlayingWithSERVERCoding implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Dump the \$_SERVER variable and run your code (it's ok that you'll have a wrong
answer) to figure out which key stores information about what browser you're using.
Then, remove the dump, but print the browser information in the `h3` tag!

EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();
        $fileBuilder->addFileContents('new_toy.php', <<<EOF
<h3>
Print the browser information of your user here
</h3>
EOF
        );

        return $fileBuilder;
    }

    public function getExecutionMode()
    {
        return self::EXECUTION_MODE_PHP_NORMAL;
    }

    public function setupContext(CodingContext $context)
    {
        // TODO - code up stuff to fake the form submit!
    }

    public function grade(CodingExecutionResult $result)
    {
        $result->assertInputContains('new_toy.php', '$_SERVER');
        $result->assertInputContains('new_toy.php', 'HTTP_USER_AGENT');
        // todo - can we assert the specific user agent?
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('new_toy.php', <<<EOF
<h3>
    <?php echo \$_SERVER['HTTP_USER_AGENT']; ?>
</h3>
EOF
        );
    }
}
