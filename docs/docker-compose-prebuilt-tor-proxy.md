> [!IMPORTANT]  
> Please note that this heavily relies on [@codedipper](https://github.com/codedipper)'s [work and is basically a copy of his wiki](https://github.com/codedipper/LibreY-Tor/wiki/LibreY-Tor-Proxy-%E2%80%90-Prebuilt-images-with-compose).


Step 0: Install and configure Docker.\
Step 1: Enter a root shell on the host machine.

Step 2: Clone the required git repositories to the host machine.

```sh
git clone https://github.com/Ahwxorg/LibreY.git LibreY/
git clone https://github.com/codedipper/LibreY-Tor.git LibreY-Tor/
```

Step 3: Apply the docker-compose.yml patch.

```sh
cd LibreY/
git apply ../LibreY-Tor/LibreY_docker-compose-yml.diff
```

Step 4: Manually edit `LibreY/docker-compose.yml` and `LibreY-Tor/torrc` to configure LibreY and Tor to your liking.

Step 5: Run it! `docker compose up -d`

To update containers:

```sh
cd LibreY/
docker compose down
docker compose pull
docker compose up -d
```

It is recommended to build the images yourself to avoid an outdated Tor version.
