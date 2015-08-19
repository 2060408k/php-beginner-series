<?php

namespace Challenges\ArtofRedirecting;

use KnpU\ActivityRunner\Activity\CodingChallenge\CodingContext;
use KnpU\ActivityRunner\Activity\CodingChallenge\CorrectAnswer;
use KnpU\ActivityRunner\Activity\CodingChallengeInterface;
use KnpU\ActivityRunner\Activity\CodingChallenge\CodingExecutionResult;
use KnpU\ActivityRunner\Activity\Exception\GradingException;
use KnpU\ActivityRunner\Activity\CodingChallenge\FileBuilder;

class RedirectUserToyList implements CodingChallengeInterface
{
    /**
     * @return string
     */
    public function getQuestion()
    {
        return <<<EOF
Now that we've saved the new pet toy, redirect the user back to /toyList.php.
EOF;
    }

    public function getFileBuilder()
    {
        $fileBuilder = new FileBuilder();
        $fileBuilder->addFileContents('new_toy.php', <<<EOF

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
    }

    public function grade(CodingExecutionResult $result)
    {
        $crawler = $result->getCrawler()->filter('form');
        if (count($crawler) == 0) {
            throw new GradingException('Did you create a `<form>` tag yet?');
        }

        if ($crawler->attr('action') != '/new_toy.php') {
            throw new GradingException('Make sure your form submits to `/new_toy.php`');
        }

        if (strtolower($crawler->attr('method')) != 'post') {
            throw new GradingException('Make sure your form has a method attribute set to `POST`');
        }

        $form = $crawler->form();
        if (!$form->has('toy_name')) {
            throw new GradingException('I don\'t see any field with `name="toy_name"`');
        }
        if (!$form->has('description')) {
            throw new GradingException('I don\'t see any field with `name="description"`');
        }
    }

    public function configureCorrectAnswer(CorrectAnswer $correctAnswer)
    {
        $correctAnswer->setFileContents('new_toy.php', <<<EOF
<form action="/new_toy.php" method="POST">
    <input type="text" name="toy_name" />
    <textarea name="description"></textarea>

    <button type="submit">Add toy</button>
</form>
EOF
        );
    }
}
