<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Media
 * @subpackage ID3
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Language.php 177 2010-03-09 13:13:34Z svollbehr $
 */

/**
 * The <var>Zend_Media_Id3_Language</var> interface implies that the
 * implementing ID3v2 frame supports its content to be given in multiple
 * languages.
 *
 * The three byte language code is used to describe the language of the frame's
 * content, according to {@link http://www.loc.gov/standards/iso639-2/
 * ISO-639-2}. The language should be represented in lower case. If the language
 * is not known the string 'und' should be used.
 *
 * @category   Zend
 * @package    Zend_Media
 * @subpackage ID3
 * @author     Sven Vollbehr <sven@vollbehr.eu>
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com) 
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Language.php 177 2010-03-09 13:13:34Z svollbehr $
 */
interface Zend_Media_Id3_Language
{
    /**
     * Returns the text language code.
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Sets the text language code.
     *
     * @param string $language The text language code.
     */
    public function setLanguage($language);
}
