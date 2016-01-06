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

namespace Start\Controller;

use Start\Service\IMessageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Start\Exception\EntityNotFoundException;
use Start\Entity\Message;
use Symfony\Component\HttpFoundation\Request;

class MessageController {

    private $messageService;

    public function __construct(IMessageService $messageService) {
        $this->messageService = $messageService;
        $messageService->createExampleMessages();
    }

    public function index() {
        $messages = $this->messageService->getAllMessages();
        $response = array();
        foreach ($messages as $message) {
            $response[] = $this->toJsonArray($message);
        }
        return new JsonResponse($response);
    }

    public function get($id) {
        $message = $this->messageService->getMessageById($id);
        if ($message === null) {
            throw new EntityNotFoundException("exception.msg.entityNotFound");
        }

        return new JsonResponse($this->toJsonArray($message));
    }

    public function create(Request $request) {
        $text = $request->get("message");
        if (empty($text)) {
            return new JsonResponse(null, 204);
        }

        // XSS filter
        $text = strip_tags($text);

        $message = $this->messageService->createMessage($text);
        return new JsonResponse($this->toJsonArray($message), 201);
    }

    public function update($id, Request $request) {
        $text = $request->get("message");
        if (empty($text)) {
            return new JsonResponse(null, 204);
        }

        // XSS filter
        $text = strip_tags($text);

        $message = $this->messageService->updateMessage($id, $text);
        return new JsonResponse($this->toJsonArray($message));
    }

    public function delete($id) {
        $this->messageService->deleteMessageById($id);
        return new JsonResponse(null, 204);
    }

    private function toJsonArray(Message $message) {
        return array(
            'id' => $message->getId(),
            'message' => $message->getMessage()
        );
    }

}