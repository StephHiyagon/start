<?php
/**
 *  Start! - An open source Doodle.com clone
 *
 *  Copyright (C) 2016  Lennart Rosam <hello@takuto.de>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

namespace Start\Service;

use Start\Entity\Message;
use Start\Exception\EntityNotFoundException;

/**
 * Interface for a basic message service
 * @author Lennart Rosam - <hello@takuto.de>
 */
interface IMessageService {

    /**
     * Returns all Message objects from the database
     *
     * @return Message[] An array of messages
     */
    public function getAllMessages();

    /**
     * Returns a Message by ID
     *
     * @param integer $id
     * @return Message|NULL The message or null
     */
    public function getMessageById($id);

    /**
     * Creates a new message
     *
     * @param string $message
     * @return Message The message object containing the ID
     */
    public function createMessage($message = "");

    /**
     * Updates the given message
     *
     * @param integer $id The ID of the message to update
     * @param string $string The message string
     *
     * @throws EntityNotFoundException When the ID does not exist
     * @return Message The updated message
     */
    public function updateMessage($id, $string = "");

    /**
     * Deletes the given message by id
     *
     * @param integer $id
     * @throws EntityNotFoundException When the ID does not exist
     * @return void
     */
    public function deleteMessageById($id);

    /**
     * Creates example messages in the Database if there are no
     * messages yet.
     *
     * @return void
     */
    public function createExampleMessages();

}