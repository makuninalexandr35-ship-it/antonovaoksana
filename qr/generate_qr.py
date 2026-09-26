"""Generate print-ready QR files for the Antonova Oksana website.

Usage:
    py -3 qr/generate_qr.py
    py -3 qr/generate_qr.py https://another-example.ru/
"""

from __future__ import annotations

import sys
from pathlib import Path

import cv2
import qrcode
from qrcode.image.svg import SvgPathImage
from PIL import Image


DEFAULT_URL = "https://antonovaoksana.ru/"
OUTPUT_DIR = Path(__file__).resolve().parent
PNG_PATH = OUTPUT_DIR / "qr-site-antonova.png"
SVG_PATH = OUTPUT_DIR / "qr-site-antonova.svg"


def add_white_background(svg_path: Path) -> None:
    """Ensure the SVG has an explicit white background for print layouts."""
    content = svg_path.read_text(encoding="utf-8")
    opening_end = content.find(">")
    if opening_end == -1:
        raise RuntimeError("Unable to add a white SVG background.")
    content = (
        content[: opening_end + 1]
        + '\n<rect width="100%" height="100%" fill="#ffffff"/>'
        + content[opening_end + 1 :]
    )
    svg_path.write_text(content, encoding="utf-8")


def main() -> None:
    url = sys.argv[1] if len(sys.argv) > 1 else DEFAULT_URL
    qr = qrcode.QRCode(
        version=None,
        error_correction=qrcode.constants.ERROR_CORRECT_H,
        box_size=64,
        border=6,
    )
    qr.add_data(url)
    qr.make(fit=True)

    qr.make_image(fill_color="#000000", back_color="#ffffff").save(PNG_PATH)
    qr.make_image(image_factory=SvgPathImage).save(SVG_PATH)
    add_white_background(SVG_PATH)

    with Image.open(PNG_PATH) as image:
        width, height = image.size
    if width < 2000 or height < 2000:
        raise RuntimeError(f"PNG is too small for print: {width}x{height}px")

    decoded, _, _ = cv2.QRCodeDetector().detectAndDecode(cv2.imread(str(PNG_PATH)))
    if decoded != url:
        raise RuntimeError(f"QR verification failed. Decoded value: {decoded!r}")

    print(f"URL: {url}")
    print(f"PNG: {PNG_PATH} ({width}x{height}px)")
    print(f"SVG: {SVG_PATH}")
    print("Verification: successful")


if __name__ == "__main__":
    main()
