#!/bin/bash

set -e

function update() {
  echo "[UPDATE SYSTEM]"
  sudo apt update
  sudo apt upgrade -y
}

function install_certbot() {
  echo "[CERTBOT]"
  sudo apt install -y certbot python3-certbot-dns-digitalocean
}

function install_motd() {
  echo "[MOTD]"

  local to_remove=(
    "00-header"
    "10-help-text"
    "50-landscape-sysinfo"
  )
  local motds=(
    "10-hostname"
    "20-sysinfo"
    "40-diskspace"
    "50-services"
    "60-docker"
  )
  local temp_dir="/tmp/motd"
  local install_dir="/etc/update-motd.d"

  sudo apt install -y figlet

  if [[ ! -d $temp_dir ]]; then
    git clone https://github.com/ojroques/motd.git "$temp_dir"
  fi

  for script in "${to_remove[@]}"; do
    sudo rm -f "$install_dir"/"$script"
  done

  pushd "$temp_dir"
  for motd in "${motds[@]}"; do
    sudo cp -vf "$motd" "$install_dir"
  done
  popd
}

function install_docker() {
  echo "[DOCKER]"

  if [[ -x "$(command -v docker)" ]]; then
    echo "Docker is already installed."
    return 0
  fi

  # Install Docker
  sudo apt install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg-agent \
    software-properties-common
  curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
  sudo add-apt-repository \
    "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
    $(lsb_release -cs) stable"
  sudo apt update && sudo apt install -y docker-ce docker-ce-cli containerd.io

  # Create 'docker' group
  sudo groupadd docker
  sudo usermod -aG docker "$USER"
  newgrp docker

  # Enable Docker at startup
  sudo systemctl enable docker
}

function install_docker-compose() {
  echo "[DOCKER-COMPOSE]"

  if [[ -x "$(command -v docker-compose)" ]]; then
    echo "Docker Compose is already installed."
    return 0
  fi

  local version="1.26.2"
  local install_dir="/usr/local/bin/docker-compose"

  sudo curl -L "https://github.com/docker/compose/releases/download/$version/docker-compose-$(uname -s)-$(uname -m)" -o $install_dir
  sudo chmod +x $install_dir
  sudo curl -L https://raw.githubusercontent.com/docker/compose/$version/contrib/completion/bash/docker-compose -o /etc/bash_completion.d/docker-compose
}

function clean() {
  echo "[CLEAN SYSTEM]"
  sudo apt autoremove -y --purge
}

update && echo
install_motd && echo
install_certbot && echo
install_docker && echo
install_docker-compose && echo
clean
