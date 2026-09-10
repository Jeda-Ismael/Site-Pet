<?php
declare(strict_types=1);

const UPLOAD_MAX_BYTES = 5 * 1024 * 1024; // 5 MB

const UPLOAD_ALLOWED_MIME = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'image/gif' => 'gif',
];

/**
 * Valida e move um arquivo de $_FILES para public/uploads com nome aleatório.
 * Retorna o caminho relativo (ex: "uploads/ab12cd34.jpg") em caso de sucesso,
 * ou lança RuntimeException com uma mensagem amigável em caso de falha.
 */
function handle_image_upload(array $file, string $uploadDir): string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException('Nenhuma imagem enviada.');
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Falha no upload da imagem (código ' . $file['error'] . ').');
    }

    if ($file['size'] > UPLOAD_MAX_BYTES) {
        throw new RuntimeException('Imagem maior que o limite de 5 MB.');
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Upload inválido.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!isset(UPLOAD_ALLOWED_MIME[$mime])) {
        throw new RuntimeException('Tipo de arquivo não permitido. Envie apenas JPG, PNG, WEBP ou GIF.');
    }

    if (getimagesize($file['tmp_name']) === false) {
        throw new RuntimeException('Arquivo não é uma imagem válida.');
    }

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Não foi possível preparar o diretório de upload.');
    }

    $ext = UPLOAD_ALLOWED_MIME[$mime];
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $destination = rtrim($uploadDir, '/') . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Falha ao salvar a imagem no servidor.');
    }

    return 'uploads/' . $filename;
}
