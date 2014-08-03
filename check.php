<?php

    require_once 'TextStatistics.php';
    
    if($text = $_POST['text'])
    {
        $T = new TextStatistics();

        ?><dl>
            <dt><a href="http://en.wikipedia.org/wiki/Flesch&ndash;Kincaid_readability_tests#Flesch_Reading_Ease">Flesch/Kincaid Reading Ease</a>:</dt>
            <dd><?= $T->flesch_kincaid_reading_ease($text) ?></dd>
            <dt><a href="http://en.wikipedia.org/wiki/Flesch&ndash;Kincaid_readability_tests#Flesch.E2.80.93Kincaid_Grade_Level">Flesch/Kincaid Grade Level</a>:</dt>
            <dd><?= $T->flesch_kincaid_grade_level($text) ?> U.S. grade level</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Gunning_fog_index">Gunning Fog Score</a>:</dt>
            <dd><?= $T->gunning_fog_score($text) ?> years of education required</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Coleman&ndash;Liau_index">Coleman-Liau Index</a>:</dt>
            <dd><?= $T->coleman_liau_index($text) ?> U.S. grade level</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Automated_Readability_Index">Automated Readability Index</a>:</dt>
            <dd><?= $T->automated_readability_index($text) ?> U.S. grade level</dd>
            <dt><a href="http://en.wikipedia.org/wiki/Dale&ndash;Chall_readability_formula">Dale-Chall Readability Score</a>:</dt>
            <dd><?= $T->dale_chall_readability_score($text) ?></dd>
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
        </ul><?

        $long_words = array();
        $strText = $T->clean_text($text);
        $intLongWordCount = 0;
        $intWordCount = $T->word_count($strText);
        $arrWords = explode(' ', $strText);
        $arrWords = explode(' ', strtolower(preg_replace('`[^A-za-z\' ]`', '', $strText)));
        for ($i = 0; $i < $intWordCount; $i++) {
            if ($T->syllable_count($arrWords[$i]) > 2) {
                $long_words[] = $T->lower_case($arrWords[$i]);
            }
        } ?>
        
        <p>Three-syllable words:</p>
        <blockquote><?= implode(', ', array_unique($long_words)) ?></blockquote><?

        $dale_chall_difficult_words = array();
        $strText = $T->clean_text($text);
        $intDifficultWordCount = 0;
        $arrWords = explode(' ', strtolower(preg_replace('`[^A-za-z\' ]`', '', $strText)));
        // Fetch Dale-Chall Words
        $T->fetchDaleChallWordList();
        for ($i = 0, $intWordCount = count($arrWords); $i < $intWordCount; $i++) {
            // Single letters are counted as easy
            if (strlen(trim($arrWords[$i])) < 2) {
                continue;
            }
            if ((!in_array(TextStatistics::pluralise($arrWords[$i]), $T->arrDaleChall)) && (!in_array(TextStatistics::unpluralise($arrWords[$i]), $T->arrDaleChall))) {
                $dale_chall_difficult_words[] = $T->lower_case($arrWords[$i]);
            }
        } ?>
        
        <p>Dale-Chall difficult words:</p>
        <blockquote><?= implode(', ', array_unique($dale_chall_difficult_words)) ?></blockquote><?

        $spache_difficult_words = array();
        $strText = $T->clean_text($text);
        $intDifficultWordCount = 0;
        $arrWords = explode(' ', strtolower(preg_replace('`[^A-za-z\' ]`', '', $strText)));
        // Fetch Spache Words
        $wordsCounted = array();
        $T->fetchSpacheWordList();
        for ($i = 0, $intWordCount = count($arrWords); $i < $intWordCount; $i++) {
            // Single letters are counted as easy
            if (strlen(trim($arrWords[$i])) < 2) {
                continue;
            }
            $singularWord = TextStatistics::unpluralise($arrWords[$i]);
            if ((!in_array(TextStatistics::pluralise($arrWords[$i]), $T->arrSpache)) && (!in_array($singularWord, $T->arrSpache))) {
                if (!in_array($singularWord, $wordsCounted)) {
                    $intDifficultWordCount++;
                    $wordsCounted[] = $singularWord;
                    $spache_difficult_words[] = $T->lower_case($arrWords[$i]);
                }
            }
        } ?>
        
        <p>Spache difficult words:</p>
        <blockquote><?= implode(', ', array_unique($spache_difficult_words)) ?></blockquote><?
        
        $difficult_words = array_intersect($long_words, $dale_chall_difficult_words, $spache_difficult_words);
        sort($difficult_words);
        
        ?>
        
        <p>Difficult words:</p>
        <blockquote><?= implode(', ', array_unique($difficult_words)) ?></blockquote><?
    }

?>
