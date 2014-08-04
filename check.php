<?php

    require_once 'TextStatistics.php';
    
    if($text = $_POST['text'])
    {
        $T = new TextStatistics();
        
        $levels = $T->grade_levels($text);
        $lists = $T->difficult_words($text);

        ?><dl>
            <dt><a href="http://en.wikipedia.org/wiki/Flesch&ndash;Kincaid_readability_tests#Flesch_Reading_Ease">Flesch/Kincaid Reading Ease</a>:</dt>
            <dd><?= $T->flesch_kincaid_reading_ease($text) ?></dd>
            <dt><a href="http://en.wikipedia.org/wiki/Flesch&ndash;Kincaid_readability_tests#Flesch.E2.80.93Kincaid_Grade_Level">Flesch/Kincaid Grade Level</a>:</dt>
            <dd><?= $levels['flesch_kincaid'] ?> U.S. grade level</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Gunning_fog_index">Gunning Fog Score</a>:</dt>
            <dd><?= $levels['gunning_fog'] ?> years of education required</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Coleman&ndash;Liau_index">Coleman-Liau Index</a>:</dt>
            <dd><?= $levels['coleman_liau'] ?> U.S. grade level</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Automated_Readability_Index">Automated Readability Index</a>:</dt>
            <dd><?= $levels['automated_readability'] ?> U.S. grade level</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Dale&ndash;Chall_readability_formula">Dale-Chall Readability Score</a>:</dt>
            <dd><?= $levels['dale_chall'] ?> U.S. grade level</dd>
            <dt>Average:</dt>
            <dd><?= $levels['average'] ?> U.S. grade level</dd>
            <!--
            <dt><a href="http://en.wikipedia.org/wiki/Spache_Readability_Formula">Spache Readability Score</a>:</dt>
            <dd><?= $T->spache_readability_score($text) ?> U.S. grade level</dd>
            -->
        </dl>
        <ul>
            <li><?= $T->text_length($text) ?> characters</li>
            <li><?= $T->letter_count($text) ?> letters</li>
            <li><?= $T->sentence_count($text) ?> sentences</li>
            <li><?= $T->word_count($text) ?> words</li>
            <li><?= sprintf('%.1f', $T->average_words_per_sentence($text)) ?> words per sentence</li>
            <li><?= $T->total_syllables($text) ?> total syllables</li>
            <li><?= sprintf('%.1f', $T->average_syllables_per_word($text)) ?> syllables per word</li>
            <li><?= $T->words_with_three_syllables($text) ?> words with three or more syllables</li>
            <li><?= sprintf('%d%%', $T->percentage_words_with_three_syllables($text)) ?> words with three or more syllables</li>
            <li><?= $T->dale_chall_difficult_word_count($text) ?> words NOT on the Dale-Chall easy word list</li>
            <li><?= $T->spache_difficult_word_count($text) ?> unique words NOT on the Spache easy word list</li>
        </ul>
        <p>Three-syllable words:</p>
        <blockquote><?= implode(', ', array_unique($lists['three_syllable'])) ?></blockquote>
        <p>Dale-Chall difficult words:</p>
        <blockquote><?= implode(', ', array_unique($lists['dale_chall'])) ?></blockquote>
        <p>Spache difficult words:</p>
        <blockquote><?= implode(', ', array_unique($lists['spache'])) ?></blockquote>
        <p>Difficult words:</p>
        <blockquote><?= implode(', ', array_unique($lists['difficult'])) ?></blockquote><?
    }

?>
