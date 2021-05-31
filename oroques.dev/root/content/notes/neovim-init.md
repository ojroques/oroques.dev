+++
title = "Neovim 0.5 features and the switch to init.lua"
date = "2020-01-07"
+++

# Neovim 0.5 features and the switch to `init.lua`

*__2021-06-01 update__: moved to vim.opt to set options, more infos on the
tree-sitter*
*__2021-04-18 update__: added links to nvim-compe and awesome-neovim, added a new
section to mention other plugins.*

Neovim 0.5 will soon be released with some major additions. The main features
this new version will ship with are:
* A built-in [LSP](https://microsoft.github.io/language-server-protocol/)
  client.
* A [tree-sitter](https://tree-sitter.github.io/tree-sitter/) for better syntax
  highlighting among other things.
* Improvements to the Lua API and especially the support of `init.lua` as an
  user config.

This post will help you write a very basic `init.lua` which include all these
new features. There are definitely some personal choices thrown in there so feel
free to ignore them or use the suggested alternatives.

We're going to avoid Vimscript entirely, and instead use Lua and the Neovim Lua
API extensively. The best reference on this subject is the
[nvim-lua-guide](https://github.com/nanotee/nvim-lua-guide). Also check
[Learn Lua in Y minutes](https://learnxinyminutes.com/docs/lua/) for a quick
overview of the Lua language.

## Contents
* [Aliases](#aliases)
* [Plugins](#plugins)
* [Set Options](#set-options)
* [Mappings](#mappings)
* [Configuring the LSP Client](#configuring-the-lsp-client)
* [Configuring the Tree-sitter](#configuring-the-tree-sitter)
* [Commands and Autocommands](#commands-and-autocommands)
* [Additional Plugins](#additional-plugins)
* [Conclusion](#conclusion)

## Aliases
We're going to use these aliases for the rest of this post:
```lua
local cmd = vim.cmd  -- to execute Vim commands e.g. cmd('pwd')
local fn = vim.fn    -- to call Vim functions e.g. fn.bufnr()
local g = vim.g      -- a table to access global variables
local opt = vim.opt  -- to set options
```

## Plugins
You have several options to manage your plugins:
* Keep using your favorite plugin manager such as
  [vim-plug](https://github.com/junegunn/vim-plug).
  This implies some Vimscript code so we'll skip that option.
* A popular choice is [packer.nvim](https://github.com/wbthomason/packer.nvim).
  It's written in Lua and definitely a good choice. It's a bit verbose so we
  won't use it here.
* We'll use [paq-nvim](https://github.com/savq/paq-nvim), a minimalist package
  manager. Install it like so:
  ```sh
  $ git clone https://github.com/savq/paq-nvim.git \
      "${XDG_DATA_HOME:-$HOME/.local/share}"/nvim/site/pack/paqs/opt/paq-nvim
  ```

Our `init.lua` starts like this:
```lua
cmd 'packadd paq-nvim'               -- load the package manager
local paq = require('paq-nvim').paq  -- a convenient alias
paq {'savq/paq-nvim', opt = true}    -- paq-nvim manages itself
paq {'shougo/deoplete-lsp'}
paq {'shougo/deoplete.nvim', run = fn['remote#host#UpdateRemotePlugins']}
paq {'nvim-treesitter/nvim-treesitter'}
paq {'neovim/nvim-lspconfig'}
paq {'junegunn/fzf', run = fn['fzf#install']}
paq {'junegunn/fzf.vim'}
paq {'ojroques/nvim-lspfuzzy'}
g['deoplete#enable_at_startup'] = 1  -- enable deoplete at startup
```

Now you can run `:PaqInstall` to install all plugins, `:PaqUpdate` to update
them and `:PaqClean` to remove unused ones.

About the plugins:
* [deoplete-lsp](https://github.com/Shougo/deoplete-lsp) and
  [deoplete.nvim](https://github.com/Shougo/deoplete.nvim): These plugins
  provide autocompletion. Two popular alternatives written entirely in Lua are
  [nvim-compe](https://github.com/hrsh7th/nvim-compe) and
  [completion.nvim](https://github.com/nvim-lua/completion-nvim). My use of
  deoplete here is a completely personal one.
* [nvim-treesitter](https://github.com/nvim-treesitter/nvim-treesitter): The
  tree-sitter is integrated to Neovim 0.5 but language modules are not. This
  plugin can configure and install them for you.
* [nvim-lspconfig](https://github.com/neovim/nvim-lspconfig): Neovim 0.5 ships
  with a native LSP client but you still need a server for each language you're
  writing in. This plugin is there to facilitate the configuration of language
  servers.
* [fzf](https://github.com/junegunn/fzf),
  [fzf.vim](https://github.com/junegunn/fzf.vim/) and
  [lspfuzzy](https://github.com/ojroques/nvim-lspfuzzy): FZF is a very popular
  fuzzy finder and lspfuzzy is a plugin I've developed to make the Neovim LSP
  client use FZF instead of the quickfix list. Again, they are personal
  choices: the most popular fuzzy finder written in Lua is currently
  [telescope.nvim](https://github.com/nvim-telescope/telescope.nvim).

Here is how LSP and FZF interact together when looking for symbol references:
![LSP and FZF](https://raw.githubusercontent.com/ojroques/nvim-lspfuzzy/main/demo.gif)

You can also find here a full list of plugins specifically developed for
Neovim: [awesome-neovim](https://github.com/rockerBOO/awesome-neovim).

## Set Options
To set options in Lua, use the `vim.opt` table which behaves exactly like the
`set` function in Vimscript. This table should cover most usages.

Otherwise the Neovim Lua API provides 3 tables if you need to specifically set
an option locally (only a buffer or in a window) or globally:
* `vim.o` to set global options: `vim.o.hidden = true`
* `vim.bo` to set buffer-scoped options: `vim.bo.expandtab = true`
* `vim.wo` to set window-scoped options: `vim.wo.number = true`

To know on which scope a particular option acts on, check Vim help pages. For
instance for `expandtab`: ![vim-scope](/vim-scope.png)

For further documentation on `vim.opt`, check the help `:h lua-vim-options`.
Here is a list of useful settings to illustrate the use of `vim.opt` (aliased
to `opt` here):
```lua
cmd 'colorscheme desert'            -- Put your favorite colorscheme here
opt.completeopt = {'menuone', 'noinsert', 'noselect'}  -- Completion options (for deoplete)
opt.expandtab = true                -- Use spaces instead of tabs
opt.hidden = true                   -- Enable background buffers
opt.ignorecase = true               -- Ignore case
opt.joinspaces = false              -- No double spaces with join
opt.list = true                     -- Show some invisible characters
opt.number = true                   -- Show line numbers
opt.relativenumber = true           -- Relative line numbers
opt.scrolloff = 4                   -- Lines of context
opt.shiftround = true               -- Round indent
opt.shiftwidth = 2                  -- Size of an indent
opt.sidescrolloff = 8               -- Columns of context
opt.smartcase = true                -- Do not ignore case with capitals
opt.smartindent = true              -- Insert indents automatically
opt.splitbelow = true               -- Put new windows below current
opt.splitright = true               -- Put new windows right of current
opt.tabstop = 2                     -- Number of spaces tabs count for
opt.termguicolors = true            -- True color support
opt.wildmode = {'list', 'longest'}  -- Command-line completion mode
opt.wrap = false                    -- Disable line wrap
```

## Mappings
The `vim.api.nvim_set_keymap()` function allows you to define a new mapping.
Specific behaviors such as `noremap` must be passed as a table to that function.
Here is a helper to create mappings with the `noremap` option set to `true` by
default:
```lua
local function map(mode, lhs, rhs, opts)
  local options = {noremap = true}
  if opts then options = vim.tbl_extend('force', options, opts) end
  vim.api.nvim_set_keymap(mode, lhs, rhs, options)
end
```

And here are mapping suggestions to illustrate the use of above helper:
```lua
map('', '<leader>c', '"+y')       -- Copy to clipboard in normal, visual, select and operator modes
map('i', '<C-u>', '<C-g>u<C-u>')  -- Make <C-u> undo-friendly
map('i', '<C-w>', '<C-g>u<C-w>')  -- Make <C-w> undo-friendly

-- <Tab> to navigate the completion menu
map('i', '<S-Tab>', 'pumvisible() ? "\\<C-p>" : "\\<Tab>"', {expr = true})
map('i', '<Tab>', 'pumvisible() ? "\\<C-n>" : "\\<Tab>"', {expr = true})

map('n', '<C-l>', '<cmd>noh<CR>')    -- Clear highlights
map('n', '<leader>o', 'm`o<Esc>``')  -- Insert a newline in normal mode
```

## Configuring the Tree-sitter
The tree-sitter is very easy to configure:
```lua
local ts = require 'nvim-treesitter.configs'
ts.setup {ensure_installed = 'maintained', highlight = {enable = true}}
```

Here the `maintained` value indicates that we wish to use all maintained
languages modules. You also need to set highlight to `true` otherwise the plugin
will be disabled. The full documentation can be found on the [Github
page](https://github.com/nvim-treesitter/nvim-treesitter).

Highlighting is not the only module available: you can also enable the `indent`
module which provides indentation based on the filetype and context. More
details on the available modules
[here](https://github.com/nvim-treesitter/nvim-treesitter#available-modules).

## Configuring the LSP Client
Thanks to the `lspconfig` plugin, configuring the LSP client is relatively easy:
* First install a server for your language: check
  [here](https://microsoft.github.io/language-server-protocol/implementors/servers/)
  for available implementations.
* Then call `setup()` to enable the server. Check the
  [nvim-lspconfig](https://github.com/neovim/nvim-lspconfig) documentation for
  advanced configuration.

Here is an example of configuration which sets up the servers for Python and
C/C++ (respectively [pyls](https://github.com/palantir/python-language-server)
and [ccls](https://github.com/MaskRay/ccls), assuming they're already
installed). We also create mappings for the most useful LSP commands.
```lua
local lsp = require 'lspconfig'
local lspfuzzy = require 'lspfuzzy'

-- For ccls we use the default settings
lsp.ccls.setup {}
-- root_dir is where the LSP server will start: here at the project root otherwise in current folder
lsp.pyls.setup {root_dir = lsp.util.root_pattern('.git', fn.getcwd())}
lspfuzzy.setup {}  -- Make the LSP client use FZF instead of the quickfix list

map('n', '<space>,', '<cmd>lua vim.lsp.diagnostic.goto_prev()<CR>')
map('n', '<space>;', '<cmd>lua vim.lsp.diagnostic.goto_next()<CR>')
map('n', '<space>a', '<cmd>lua vim.lsp.buf.code_action()<CR>')
map('n', '<space>d', '<cmd>lua vim.lsp.buf.definition()<CR>')
map('n', '<space>f', '<cmd>lua vim.lsp.buf.formatting()<CR>')
map('n', '<space>h', '<cmd>lua vim.lsp.buf.hover()<CR>')
map('n', '<space>m', '<cmd>lua vim.lsp.buf.rename()<CR>')
map('n', '<space>r', '<cmd>lua vim.lsp.buf.references()<CR>')
map('n', '<space>s', '<cmd>lua vim.lsp.buf.document_symbol()<CR>')
```

## Commands and Autocommands
Unfortunately Neovim doesn't have an interface to create commands and
autocommands yet. Work is in progress to implement such an interface, see
[PR#11613](https://github.com/neovim/neovim/pull/11613) for commands and
[PR#12378](https://github.com/neovim/neovim/pull/12378) for autocommands.

You can still define commands or autocommands using Vimscript via `vim.cmd`.
For instance Neovim 0.5 introduces the
[highlight on yank](https://github.com/neovim/neovim/pull/12279) feature which
briefly highlights yanked text. You can enable it as an autocommand like so:
```lua
cmd 'au TextYankPost * lua vim.highlight.on_yank {on_visual = false}'  -- disabled in visual mode
```

## Additional Plugins
I've included in the config the plugins I think are the most essential ones for
a basic usage of Neovim. Here are some other plugins that leverage the new
features of Neovim and that could greatly improve your workflow:
* [gitsigns.nvim](https://github.com/lewis6991/gitsigns.nvim): a replacement of
  [vim-gitgutter](https://github.com/airblade/vim-gitgutter) in Lua.
* [indent-blankline.nvim](https://github.com/lukas-reineke/indent-blankline.nvim)
  (the *lua* branch): uses the
  [new virtual text feature](https://github.com/neovim/neovim/pull/13952) to
  display indent lines, including on empty lines.
* [nvim-dap](https://github.com/mfussenegger/nvim-dap): implements the
  [Debug Adapter Protocol](https://microsoft.github.io/debug-adapter-protocol/)
  in Neovim (the equivalent of LSP for debuggers basically). Beware that it can
  be a bit tricky to set up and that not many adapters exist yet.
* [nvim-treesitter/nvim-treesitter-textobjects](https://github.com/nvim-treesitter/nvim-treesitter-textobjects):
  a plugin to create text objects based on the treesitter engine, to select
  parameters / functions for instance or to swap code elements.
* [nvim-lightbulb](https://github.com/kosayoda/nvim-lightbulb): a simple plugin
  to display a light bulb in the sign column whenever code actions are
  available for the current cursor position.

## Conclusion
Here is the complete init.lua:
```lua
-------------------- HELPERS -------------------------------
local cmd = vim.cmd  -- to execute Vim commands e.g. cmd('pwd')
local fn = vim.fn    -- to call Vim functions e.g. fn.bufnr()
local g = vim.g      -- a table to access global variables
local opt = vim.opt  -- to set options

local function map(mode, lhs, rhs, opts)
  local options = {noremap = true}
  if opts then options = vim.tbl_extend('force', options, opts) end
  vim.api.nvim_set_keymap(mode, lhs, rhs, options)
end

-------------------- PLUGINS -------------------------------
cmd 'packadd paq-nvim'               -- load the package manager
local paq = require('paq-nvim').paq  -- a convenient alias
paq {'savq/paq-nvim', opt = true}    -- paq-nvim manages itself
paq {'shougo/deoplete-lsp'}
paq {'shougo/deoplete.nvim', run = fn['remote#host#UpdateRemotePlugins']}
paq {'nvim-treesitter/nvim-treesitter'}
paq {'neovim/nvim-lspconfig'}
paq {'junegunn/fzf', run = fn['fzf#install']}
paq {'junegunn/fzf.vim'}
paq {'ojroques/nvim-lspfuzzy'}
g['deoplete#enable_at_startup'] = 1  -- enable deoplete at startup

-------------------- OPTIONS -------------------------------
cmd 'colorscheme desert'            -- Put your favorite colorscheme here
opt.completeopt = {'menuone', 'noinsert', 'noselect'}  -- Completion options (for deoplete)
opt.expandtab = true                -- Use spaces instead of tabs
opt.hidden = true                   -- Enable background buffers
opt.ignorecase = true               -- Ignore case
opt.joinspaces = false              -- No double spaces with join
opt.list = true                     -- Show some invisible characters
opt.number = true                   -- Show line numbers
opt.relativenumber = true           -- Relative line numbers
opt.scrolloff = 4                   -- Lines of context
opt.shiftround = true               -- Round indent
opt.shiftwidth = 2                  -- Size of an indent
opt.sidescrolloff = 8               -- Columns of context
opt.smartcase = true                -- Do not ignore case with capitals
opt.smartindent = true              -- Insert indents automatically
opt.splitbelow = true               -- Put new windows below current
opt.splitright = true               -- Put new windows right of current
opt.tabstop = 2                     -- Number of spaces tabs count for
opt.termguicolors = true            -- True color support
opt.wildmode = {'list', 'longest'}  -- Command-line completion mode
opt.wrap = false                    -- Disable line wrap

-------------------- MAPPINGS ------------------------------
map('', '<leader>c', '"+y')       -- Copy to clipboard in normal, visual, select and operator modes
map('i', '<C-u>', '<C-g>u<C-u>')  -- Make <C-u> undo-friendly
map('i', '<C-w>', '<C-g>u<C-w>')  -- Make <C-w> undo-friendly

-- <Tab> to navigate the completion menu
map('i', '<S-Tab>', 'pumvisible() ? "\\<C-p>" : "\\<Tab>"', {expr = true})
map('i', '<Tab>', 'pumvisible() ? "\\<C-n>" : "\\<Tab>"', {expr = true})

map('n', '<C-l>', '<cmd>noh<CR>')    -- Clear highlights
map('n', '<leader>o', 'm`o<Esc>``')  -- Insert a newline in normal mode

-------------------- TREE-SITTER ---------------------------
local ts = require 'nvim-treesitter.configs'
ts.setup {ensure_installed = 'maintained', highlight = {enable = true}}

-------------------- LSP -----------------------------------
local lsp = require 'lspconfig'
local lspfuzzy = require 'lspfuzzy'

-- For ccls we use the default settings
lsp.ccls.setup {}
-- root_dir is where the LSP server will start: here at the project root otherwise in current folder
lsp.pyls.setup {root_dir = lsp.util.root_pattern('.git', fn.getcwd())}
lspfuzzy.setup {}  -- Make the LSP client use FZF instead of the quickfix list

map('n', '<space>,', '<cmd>lua vim.lsp.diagnostic.goto_prev()<CR>')
map('n', '<space>;', '<cmd>lua vim.lsp.diagnostic.goto_next()<CR>')
map('n', '<space>a', '<cmd>lua vim.lsp.buf.code_action()<CR>')
map('n', '<space>d', '<cmd>lua vim.lsp.buf.definition()<CR>')
map('n', '<space>f', '<cmd>lua vim.lsp.buf.formatting()<CR>')
map('n', '<space>h', '<cmd>lua vim.lsp.buf.hover()<CR>')
map('n', '<space>m', '<cmd>lua vim.lsp.buf.rename()<CR>')
map('n', '<space>r', '<cmd>lua vim.lsp.buf.references()<CR>')
map('n', '<space>s', '<cmd>lua vim.lsp.buf.document_symbol()<CR>')

-------------------- COMMANDS ------------------------------
cmd 'au TextYankPost * lua vim.highlight.on_yank {on_visual = false}'  -- disabled in visual mode
```

I hope you've found this guide useful. You can find my own init.lua from which
most of the above code has been taken from here:
[init.lua](https://github.com/ojroques/dotfiles/blob/master/nvim/init.lua).

Also you might be interested in the Vim/Neovim plugins I've developed:
* [nvim-lspfuzzy](https://github.com/ojroques/nvim-lspfuzzy):
  extend the Neovim built-in LSP client to use FZF.
* [vim-oscyank](https://github.com/ojroques/vim-oscyank):
  copy text from anywhere (including through SSH) with
  [OSC52](/notes/vim-osc52).
* [nvim-bufdel](https://github.com/ojroques/nvim-bufdel):
  improve buffer deletion (see [post](/notes/neovim-bufdel)).
* [vim-scrollstatus](https://github.com/ojroques/vim-scrollstatus):
  display a scrollbar in your statusline (for Neovim 0.5 there are even better
  alternatives [here](https://github.com/dstein64/nvim-scrollview) or
  [here](https://github.com/Xuyuanp/scrollbar.nvim)).
