# Ypsilon Picker (PHP CLI) 🎨

**Authors:** ChatGPT by OpenAI & Engin Ypsilon by `class:Parent()`

## Overview

Ypsilon Picker is a simple terminal-based color picker for Wayland-based GNOME desktops. Click anywhere on the screen and instantly retrieve the RGB and HEX values of the selected color.


### Features

- ✅ **Wayland-compatible** – Works on modern GNOME, unlike X11-based pickers.
- ✅ **Minimal & fast** – A single PHP script with no dependencies beyond PHP and GNOME’s built-in tools.
- ✅ **Optional clipboard support** – Automatically copies the HEX value to your clipboard.
- ✅ **Easy access** – Use it as a CLI tool or create a desktop launcher for quick access.

### Alternative

***Ypsilon Picker - GNOME Shell Extension***

[https://github.com/eypsilon/ypsilonpicker-gnome](https://github.com/eypsilon/ypsilonpicker-gnome)

---

## **Installation**

Ypsilon Picker is a lightweight PHP script (~50 lines of code) that requires no dependencies beyond GNOME. You can run it directly or set up a shortcut for convenience.

### **1. Install the script**

Clone this repository into your local <ins>bin</ins> directory (or another preferred location):

```sh
mkdir -p ~/bin/ypsilonpicker-php
cd ~/bin/ypsilonpicker-php
git clone https://github.com/eypsilon/ypsilonpicker-php.git .
chmod +x YpsilonPicker.php
```


### Usage

Simply run:

```sh
~/bin/ypsilonpicker-php/YpsilonPicker.php
```

__Example output after selection__

```sh
Click anywhere on the screen to pick a color...
RGB: 23 20 33
HEX: #171421
HEX copied to clipboard!
```

\* If `wl-clipboard` is installed, the HEX value is automatically copied to your clipboard:

```sh
sudo apt install wl-clipboard
```

---

#### Optional Setup

***1. Create a Bash Alias (for CLI users)***

To shorten the command, add this alias to your <ins>.bashrc</ins> or <ins>.zshrc</ins>:

```sh
# sudo nano ~/.bash_aliases
alias ypick="~/bin/ypsilonpicker-php/YpsilonPicker.php"

# Reload your shell
source ~/.bashrc
# or source ~/.zshrc
```

Now, simply type `ypick` in the terminal to launch it! 🎨🚀

---

***2. Create a Desktop Launcher (for GUI users)***

If you want to launch **Ypsilon Picker** from your application menu, create a <ins>.desktop</ins> file.

* **Create the launcher file:**

```sh
# Do NOT use `sudo` to avoid permission issues
nano ~/.local/share/applications/ypsilonpicker.desktop
```

* **Add the following content:**

```ini
[Desktop Entry]
Name=Ypsilon Picker
Comment=Pick colors from the screen
Exec=gnome-terminal --geometry=54x6+100+100 -- bash -c "php ~/bin/ypsilonpicker-php/YpsilonPicker.php; exec bash"
Icon=preferences-color
Terminal=true
Type=Application
Categories=Utility;
```

* **Save and close the file (`Ctrl+o`, then `Enter`, then `Ctrl-x`).**

Now, you can search for "Ypsilon Picker" in your application menu and run it like a boss! 🎨

---

#### Troubleshooting (if the launcher doesn’t work)

Ensure correct ownership (only needed if you used `sudo` when creating the file):

```sh
sudo chown $USER:$USER ~/.local/share/applications/ypsilonpicker.desktop
```

Mark the file as trusted (to avoid untrusted application warnings):

```sh
gio set ~/.local/share/applications/ypsilonpicker.desktop metadata::trusted true
```

Update the desktop database (if the launcher doesn’t appear):

```sh
update-desktop-database ~/.local/share/applications/
```

---

#### Alternative: Bash Version

If you prefer a Bash script instead of PHP, use this:

```sh
#!/bin/bash
# Ypsilon Picker (Bash)
echo "Click anywhere on the screen to pick a color..."
sleep 0.5

output=$(gdbus call --session --dest org.gnome.Shell.Screenshot --object-path /org/gnome/Shell/Screenshot --method org.gnome.Shell.Screenshot.PickColor)
colors=($(echo $output | grep -o "[0-9\.]*"))

LC_NUMERIC=C
for ((i = 0; i < ${#colors[@]}; i++)); do
    colors[$i]=$(echo "${colors[$i]} * 255" | bc | awk '{printf "%.0f\n", $1}')
done

echo "RGB: ${colors[0]} ${colors[1]} ${colors[2]}"
printf "HEX: #%02x%02x%02x\n" "${colors[0]}" "${colors[1]}" "${colors[2]}"
```

Just save it as `ypsilonpicker.sh`, make it executable, and run it:

```sh
chmod +x ypsilonpicker.sh
./ypsilonpicker.sh
```

---

##### License

[**MIT License**](./LICENSE)


<!-- Tags for searchability -->
**Tags:** `Color-Picker` `Wayland-Color-Picker` `Linux-Color-Picker` `GNOME-Color-Picker` `CLI-Color-Picker` `RGB-Hex-Extractor` `PHP-Terminal-Tool` `Open-Source-Color-Picker`
