+++
title = "Copy text through SSH in Vim/Neovim with OSC52"
date = "2020-11-27"
+++

# Copy text through SSH in Vim/Neovim with OSC52

**TL;DR**: OSC52 is an
[ANSI escape sequence](https://en.wikipedia.org/wiki/ANSI_escape_code#Escape_sequences)
that allows you to copy text into your system clipboard from anywhere,
including from remote SSH sessions.
Check [vim-oscyank](https://github.com/ojroques/vim-oscyank), a plugin which
integrates OSC52 into Vim.

## What is OSC52?
OSC stands for *Operating System Command*, a category of
[ANSI escape sequences](https://en.wikipedia.org/wiki/ANSI_escape_code#Escape_sequences)
which instruct the terminal emulator to perform certain actions.

OSC52 is one of these sequence and tells the terminal that the string it
carries must be copied to the system clipboard. Typically, an application
encodes a string in base64, prefixes it with the OSC52 sequence and outputs it.
The terminal parses the OSC sequence and updates the clipboard.

## Why is it useful?
OSC52 is totally *location-independent*. The terminal does not care from where
the sequence was emitted, even if it comes from a remote SSH session. It is
especially useful in Vim since you are now able copy to your system clipboard
from basically anywhere.

## How do I use it?
The only requirement is that the terminal must support the sequence. Here is
a non-exhaustive list of the status of popular terminal emulators regarding
OSC52 (as of November 2020):

| Terminal | OCS52 support |
|----------|:-------------:|
| [Alacritty](https://github.com/alacritty/alacritty) | **yes** |
| [GNOME Terminal](https://github.com/GNOME/gnome-terminal) (and other VTE-based terminals) | [not yet](https://bugzilla.gnome.org/show_bug.cgi?id=795774) |
| [hterm (Chromebook)](https://chromium.googlesource.com/apps/libapps/+/master/README.md) | [**yes**](https://chromium.googlesource.com/apps/libapps/+/master/nassh/doc/FAQ.md#Is-OSC-52-aka-clipboard-operations_supported) |
| [iTerm2](https://iterm2.com/) | **yes** |
| [kitty](https://github.com/kovidgoyal/kitty) | [**yes**](https://sw.kovidgoyal.net/kitty/protocol-extensions.html#pasting-to-clipboard) |
| [screen](https://www.gnu.org/software/screen/) | **yes** |
| [tmux](https://github.com/tmux/tmux) | **yes** |
| [Windows Terminal](https://github.com/microsoft/terminal) | **yes** |
| [rxvt](http://rxvt.sourceforge.net/) | **yes** (to be confirmed) |
| [urxvt](http://software.schmorp.de/pkg/rxvt-unicode.html) | **yes** (with a script, see [here](https://github.com/ojroques/vim-oscyank/issues/4)) |

I've developed a very simple plugin to use the OSC52 protocol:
[vim-oscyank](https://github.com/ojroques/vim-oscyank). It basically takes
a visual selection, encodes it in base64 and wraps it with OSC52 for your
convenience. Check the plugin's README for installation and usage.
