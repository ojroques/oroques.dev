#!/bin/bash -l

# Git post-receive hook
# https://jekyllrb.com/docs/deployment/

#################### general ###############################
WWW=/var/www
GIT_USER=$HOME/gitea-repositories/olivier
GIT_REPO=$GIT_USER/oroques.dev.git
TMP_GIT_CLONE=$GIT_USER/tmp/oroques.dev

git clone $GIT_REPO $TMP_GIT_CLONE


#################### oroques ###############################
export GEM_HOME=$HOME/gems
export PATH=$GEM_HOME/bin:$PATH

JEKYLL_SRC=$TMP_GIT_CLONE/oroques
JEKYLL_DST=$WWW/oroques
GEMFILE=$JEKYLL_SRC/Gemfile

BUNDLE_GEMFILE=$GEMFILE bundle install
BUNDLE_GEMFILE=$GEMFILE bundle exec jekyll build -s $JEKYLL_SRC -d $JEKYLL_DST


#################### movies  ###############################
MOVIES_SRC=$TMP_GIT_CLONE/movies
MOVIES_DST=$WWW/movies

rm -rf $MOVIES_DST
cp -r $MOVIES_SRC $MOVIES_DST
mv -n $MOVIES_DST/credentials.php $WWW


#################### cleaning  ###############################
rm -rf $TMP_GIT_CLONE
exit
