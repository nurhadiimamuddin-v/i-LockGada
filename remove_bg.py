from PIL import Image

input_path = 'images/pegadaian_keyhole_v5.png'
output_path = 'images/pegadaian_keyhole_v6.png'

try:
    img = Image.open(input_path).convert("RGBA")
    datas = img.getdata()

    newData = []
    # Remove pixels that are close to white (RGB all > 220)
    for item in datas:
        if item[0] > 220 and item[1] > 220 and item[2] > 220:
            newData.append((255, 255, 255, 0)) # Transparent
        else:
            newData.append(item)

    img.putdata(newData)
    img.save(output_path, "PNG")
    print("Background removed and saved to v6.")
except Exception as e:
    print(f"Error: {e}")
