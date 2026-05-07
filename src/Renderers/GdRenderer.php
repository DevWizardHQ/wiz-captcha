<?php

namespace DevWizardHQ\Captcha\Renderers;

use DevWizardHQ\Captcha\CaptchaChallenge;
use DevWizardHQ\Captcha\Contracts\ImageRenderer;
use RuntimeException;

class GdRenderer implements ImageRenderer
{
    public function render(CaptchaChallenge $challenge, array $config): string
    {
        if (! extension_loaded('gd')) {
            throw new RuntimeException('The GD extension is required to generate CAPTCHA images.');
        }

        $width = (int) ($config['width'] ?? 180);
        $height = (int) ($config['height'] ?? 60);

        $image = imagecreatetruecolor($width, $height);

        if (! $image) {
            throw new RuntimeException('Could not create CAPTCHA image.');
        }

        [$r, $g, $b] = $config['background'] ?? [245, 245, 245];

        $background = imagecolorallocate($image, $r, $g, $b);

        imagefill($image, 0, 0, $background);

        $this->drawNoise($image, $width, $height, (int) ($config['noise'] ?? 80));
        $this->drawLines($image, $width, $height, (int) ($config['lines'] ?? 5));
        $this->drawText($image, $challenge->question, $height, $config);

        ob_start();
        imagepng($image);
        $contents = ob_get_clean();

        imagedestroy($image);

        if (! is_string($contents)) {
            throw new RuntimeException('Could not render CAPTCHA image.');
        }

        return $contents;
    }

    private function drawNoise(\GdImage $image, int $width, int $height, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $color = imagecolorallocate(
                $image,
                random_int(120, 220),
                random_int(120, 220),
                random_int(120, 220),
            );

            imagesetpixel(
                $image,
                random_int(0, $width - 1),
                random_int(0, $height - 1),
                $color,
            );
        }
    }

    private function drawLines(\GdImage $image, int $width, int $height, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $color = imagecolorallocate(
                $image,
                random_int(80, 180),
                random_int(80, 180),
                random_int(80, 180),
            );

            imageline(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                random_int(0, $width),
                random_int(0, $height),
                $color,
            );
        }
    }

    private function drawText(\GdImage $image, string $text, int $height, array $config): void
    {
        $fontSize = (int) ($config['font_size'] ?? 5);
        $x = 15;
        $y = (int) (($height / 2) - 8);

        for ($i = 0; $i < strlen($text); $i++) {
            $color = imagecolorallocate(
                $image,
                random_int(0, 90),
                random_int(0, 90),
                random_int(0, 90),
            );

            imagestring(
                $image,
                $fontSize,
                $x + ($i * 18) + random_int(-2, 2),
                $y + random_int(-5, 5),
                $text[$i],
                $color,
            );
        }
    }
}
