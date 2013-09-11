<?php
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the Cooperative Library Network Berlin-Brandenburg,
 * the Saarland University and State Library, the Saxon State Library -
 * Dresden State and University Library, the Bielefeld University Library and
 * the University Library of Hamburg University of Technology with funding from
 * the German Research Foundation and the European Regional Development Fund.
 *
 * LICENCE
 * OPUS is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the Licence, or any later version.
 * OPUS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License
 * along with OPUS; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

/**
 * Decorator fuer die Ausgabe eines Datei-Hashes (Form_Element_FileHash).
 *
 * @category    Application
 * @package     Form_Decorator
 * @author      Jens Schwidder <schwidder@zib.de>
 * @copyright   Copyright (c) 2008-2013, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */
class Form_Decorator_FileHash extends Zend_Form_Decorator_Abstract {

    public function render($content) {
        $element = $this->getElement();

        if (!$element instanceof Form_Element_FileHash) {
            return $content;
        }

        $view = $element->getView();

        if (!$view instanceof Zend_View_Interface) {
            return $content;
        }

        $hash = new Admin_Model_Hash($element->getFile(), $element->getValue());

        $hashSoll = $hash->getSoll();
        $hashIst = $hash->getIst();

        // TODO fix: label nur anzeigen wenn soll und ist angezeigt werden
        $markup = '<div class="textarea hashsoll"><span class="hash-label">Soll: </span>'
            . htmlspecialchars($hashSoll) . '</div>';

        $markup .= $view->formHidden($element->getFullyQualifiedName() . '[Soll]', $hashSoll);

        // TODO fix: ist nur anzeigen wenn Abweichung
        if ($hashSoll == $hashIst) {
            $markup .= '<div class="textarea hashist"><span class="hash-label">Ist: </span>'
                . htmlspecialchars($hashIst) . '</div>';
            $markup .= $view->formHidden($element->getFullyQualifiedName() . '[Ist]', $hashIst);
        }

        switch ($this->getPlacement()) {
            case self::PREPEND:
                return $markup . $content;
            case self::APPEND:
            default:
                return $content . $markup;
        }
    }

}