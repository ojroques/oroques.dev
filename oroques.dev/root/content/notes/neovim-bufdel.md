+++
title = "Improving buffer deletion in Neovim"
date = "2021-01-30"
+++

# Improving buffer deletion in Neovim

## The Plugin
I've created a small plugin
[nvim-bufdel](https://github.com/ojroques/nvim-bufdel) that improves the
deletion of buffers in Neovim.

Here are the improvements I'm talking about:
* **Preserve the layout of windows.** Deleting a buffer will no longer close any
  window unexpectedly.
* **Cycle through buffers according to their number.** This is especially
  helpful when using a bufferline: we get the same behavior as closing tabs in
  Chrome / Firefox.
* **Terminal buffers are deleted without prompt.**
* **Exit Neovim when last buffer is deleted.**

And here's a demo:
![demo](https://raw.githubusercontent.com/ojroques/nvim-bufdel/main/demo.gif)

Here the same buffer is displayed in left and top-right window. Deleting that
buffer preserves the window layout and the first buffer with a number greater
than the deleted one is selected instead (the one immediately to the right in
the bufferline).

## Direct Integration
The plugin is fairly minimal and fits in a
[single file](https://github.com/ojroques/nvim-bufdel/blob/main/lua/bufdel.lua).
You don't actually need to install the plugin, you can very well download the
file and include it in your config folder.

You can also integrate the plugin main command directly into your config. Here's
a condensed version of the plugin (minus minor improvements) in Lua:
```lua
function delete_buffer()
  local buflisted = fn.getbufinfo({buflisted = 1})
  local cur_winnr, cur_bufnr = fn.winnr(), fn.bufnr()
  if #buflisted < 2 then cmd 'confirm qall' return end
  for _, winid in ipairs(fn.getbufinfo(cur_bufnr)[1].windows) do
    cmd(string.format('%d wincmd w', fn.win_id2win(winid)))
    cmd(cur_bufnr == buflisted[#buflisted].bufnr and 'bp' or 'bn')
  end
  cmd(string.format('%d wincmd w', cur_winnr))
  local is_terminal = fn.getbufvar(cur_bufnr, '&buftype') == 'terminal'
  cmd(is_terminal and 'bd! #' or 'silent! confirm bd #')
end
```

Or in Vimscript:
```vim
function! s:delete_buffer()
  let l:buflisted = getbufinfo({'buflisted': 1})
  let [l:cur_winnr, l:cur_bufnr] = [winnr(), bufnr()]
  if len(l:buflisted) < 2 | confirm qall | return | endif
  for l:winid in getbufinfo(l:cur_bufnr)[0].windows
    execute(win_id2win(l:winid) . 'wincmd w')
    if l:cur_bufnr == l:buflisted[-1].bufnr | bp | else | bn | endif
  endfor
  execute(l:cur_winnr . 'wincmd w')
  let l:is_terminal = getbufvar(l:cur_bufnr, '&buftype') == 'terminal'
  if l:is_terminal | bd! # | else | silent! confirm bd # | endif
endfunction
```
