<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\FileBundle\Controller;

use League\Flysystem\FilesystemInterface;
use Mindy\Bundle\MindyBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    const UPLOAD_NAME = 'files';

    public function createDirectory(Request $request, FilesystemInterface $fs)
    {
        $path = $request->query->get('path', '/');
        $directoryName = $request->request->get('directory');

        if (empty($directoryName)) {
            return $this->json([
                'status' => false,
                'message' => 'Не передано имя директории',
            ]);
        } elseif (strpos($directoryName, '/') !== false) {
            return $this->json([
                'status' => false,
                'message' => 'Не корректное имя директории',
            ]);
        }
        $dirPath = implode('/', [$path, $directoryName]);

        if ($fs->has($dirPath)) {
            return $this->json([
                'status' => false,
                'message' => 'Директория с таким названием уже существует',
            ]);
        }

        if ($fs->createDir($dirPath)) {
            return $this->json([
                'status' => true,
                'message' => 'Директория создана',
            ]);
        }

        return $this->json([
            'status' => false,
            'message' => 'Ошибка при создании директории',
        ]);
    }

    public function wysiwyg(Request $request, string $wysiwyg, FilesystemInterface $fs)
    {
        $path = urldecode($request->query->get('path', '/'));

        $objects = [];
        foreach ($fs->listContents($path) as $object) {
            $objects[] = [
                'path' => '/'.$object['path'],
                'name' => basename($object['path']),
                'date' => isset($object['timestamp']) ? date(DATE_W3C, $object['timestamp']) : null,
                'is_dir' => $object['type'] === 'dir',
                'size' => isset($object['size']) ? $object['size'] : 0,
                'url' => $object['path'],
            ];
        }

        $breadcrumbs = [
            [
                'url' => $this->generateUrl('file_wysiwyg', ['wysiwyg' => $wysiwyg]),
                'name' => 'Файлы',
            ],
        ];
        $prev = [];
        foreach (array_filter(explode('/', $path)) as $part) {
            $prev[] = $part;

            $query = [
                'wysiwyg' => $wysiwyg,
                'path' => '/'.implode('/', $prev),
            ];
            $breadcrumbs[] = [
                'url' => $this->generateUrl('file_wysiwyg', $query),
                'name' => $part,
            ];
        }

        return $this->render('file/wysiwyg.html', [
            'breadcrumbs' => $breadcrumbs,
            'objects' => $objects,
            'wysiwyg' => $wysiwyg,
        ]);
    }

    public function list(Request $request, FilesystemInterface $fs)
    {
        $path = urldecode($request->query->get('path', '/'));

        $objects = [];
        foreach ($fs->listContents($path) as $object) {
            $objects[] = [
                'path' => '/'.$object['path'],
                'name' => basename($object['path']),
                'date' => isset($object['timestamp']) ? date(DATE_W3C, $object['timestamp']) : null,
                'is_dir' => $object['type'] === 'dir',
                'size' => isset($object['size']) ? $object['size'] : 0,
                'url' => $object['path'],
            ];
        }

        $breadcrumbs = [
            [
                'url' => $this->generateUrl('file_list'),
                'name' => 'Файлы',
            ],
        ];
        $prev = [];
        foreach (array_filter(explode('/', $path)) as $part) {
            $prev[] = $part;

            $query = ['path' => '/'.implode('/', $prev)];
            $url = $this->generateUrl('file_list', $query);
            $breadcrumbs[] = ['url' => $url, 'name' => $part];
        }

        return $this->render('file/list.html', [
            'breadcrumbs' => $breadcrumbs,
            'objects' => $objects,
        ]);
    }

    public function delete(Request $request, FilesystemInterface $fs)
    {
        $path = $request->query->get('path', '/');
        if ($fs->has($path)) {
            $meta = $fs->getMetadata($path);
            $status = $meta['type'] === 'file' ? $fs->delete($path) : $fs->deleteDir($path);
            if (false === $status) {
                throw new \RuntimeException(sprintf(
                    'Error while remove %s',
                    $path
                ));
            }

            return $this->json(['status' => true]);
        }

        return $this->json(['status' => false, 'error' => 'Path not found']);
    }

    public function upload(Request $request, FilesystemInterface $fs)
    {
        $path = $request->query->get('path', '/');

        $files = $request->files->get(self::UPLOAD_NAME);
        foreach ($files as $file) {
            /** @var UploadedFile $file */
            if ($file->isValid()) {
                $stream = fopen($file->getRealPath(), 'r+');
                $fs->writeStream(sprintf('%s/%s', $path, $file->getClientOriginalName()), $stream);
                fclose($stream);
            }
        }

        return new Response('ok');
    }
}
