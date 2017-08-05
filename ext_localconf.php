<?php

/*  | This extension is made for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2011-2017 Armin Ruediger Vieweg <armin@v.ieweg.de>
 *  |     2015 Dennis Roemmich <dennis@roemmich.eu>
 *  |     2016-2017 Christian Wolfram <c.wolfram@chriwo.de>
 */

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$boot = function ($extensionKey) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'PwCommentsTeam.' . $extensionKey,
        'Pi1',
        [
            'Comment' => 'index,new,create,upvote,downvote,confirmComment',
        ],
        [
            'Comment' => 'index,new,create,upvote,downvote,confirmComment',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'PwCommentsTeam.' . $extensionKey,
        'Pi2',
        [
            'Comment' => 'sendAuthorMailWhenCommentHasBeenApproved',
        ],
        [
            'Comment' => 'sendAuthorMailWhenCommentHasBeenApproved',
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['PwComments']['modules']
        = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['PwComments']['plugins'];

    $GLOBALS['TYPO3_CONF_VARS']['FE']['cHashExcludedParameters'] .= ',' . implode(
        ',',
        [
            'tx_pwcomments_pi1[commentToReplyTo]',
            'tx_pwcomments_pi1[controller]',
            'tx_pwcomments_pi1[action]',
            'tx_pwcomments_pi1[__referrer][@extension]',
            'tx_pwcomments_pi1[__referrer][@vendor]',
            'tx_pwcomments_pi1[__referrer][@controller]',
            'tx_pwcomments_pi1[__referrer][@action]',
            'tx_pwcomments_pi1[__referrer][arguments]',
            'tx_pwcomments_pi1[__referrer][@request]',
            'tx_pwcomments_pi1[__trustedProperties]',
            'tx_pwcomments_pi1[newComment][authorName]',
            'tx_pwcomments_pi1[newComment][authorMail]',
            'tx_pwcomments_pi1[authorWebsite]',
            'tx_pwcomments_pi1[newComment][message]',
            'tx_pwcomments_pi1[newComment][parentComment][__identity]'
        ]
    );
        // After save hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
        'PwCommentsTeam\PwComments\Hooks\ProcessDatamap';

    if (TYPO3_MODE === 'BE') {
        $extensionConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pw_comments']);
        if (!isset($extensionConfig['pageModuleNotice']) || $extensionConfig['pageModuleNotice'] !== '0') {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Backend\\Controller\\PageLayoutController'] = [
                'className' => 'PwCommentsTeam\\PwComments\\XClass\\PageLayoutController',
            ];
        }

        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        $iconRegistry->registerIcon(
            'ext-pwcomments-type-vote_down',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:pw_comments/Resources/Public/Icons/tx_pwcomments_domain_model_vote_down.png']
        );

        $iconRegistry->registerIcon(
            'ext-pwcomments-type-vote_up',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:pw_comments/Resources/Public/Icons/tx_pwcomments_domain_model_vote_up.png']
        );
    }
};

$boot($_EXTKEY);
unset($boot);
