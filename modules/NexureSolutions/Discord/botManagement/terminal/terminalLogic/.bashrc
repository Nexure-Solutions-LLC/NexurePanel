shopt -s expand_aliases

# man output formatting
export MAN_KEEP_FORMATTING=1
export PATH=$PATH:/usr/games
export TERM="xterm-256" #force colors for dircolors
alias grep="grep --color=always"

if [ -x /usr/bin/dircolors ]; then
    #Nice colors
    eval "`dircolors -b`"
    alias ls="ls --color=always"
fi