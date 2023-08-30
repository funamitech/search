#!/bin/sh

# Load all environment variables from 'attributes.sh' using the command 'source /path/attributes.sh'
source "docker/attributes.sh"

# The lines below will replace the environment variables in the templates with the corresponding variables listed above if the config file is not yet provided. To accomplish this, the GNU 'envsubst' package will be used
# Although not recommended (if you do not know what you are doing), you still have the option to add new substitution file templates using any required environment variables

[[ ! -s ${CONFIG_PHP_TEMPLATE} ]] && cat 'docker/config.php' | envsubst > ${CONFIG_PHP_TEMPLATE};
[[ ! -s ${CONFIG_OPEN_SEARCH_TEMPLATE} ]] && cat 'docker/opensearch.xml' | envsubst > ${CONFIG_OPEN_SEARCH_TEMPLATE};

export OPEN_SEARCH_HOST_FOR_NGINX="$(echo "${OPEN_SEARCH_HOST}" | cut -d "/" -f 3 | cut -d ":" -f 1)"
if [[ ! -s ${CONFIG_NGINX_TEMPLATE} ]]; then

    cat 'docker/nginx.conf' | envsubst '${OPEN_SEARCH_HOST_FOR_NGINX}' > ${CONFIG_NGINX_TEMPLATE};
    mv "docker/fastcgi.conf" /etc/nginx/fastcgi.conf

    chown nginx:nginx "/etc/nginx/fastcgi.conf"
    chown nginx:nginx "/etc/nginx/http.d/librey.conf"

fi

# If it is empty or proxy is not enabled, we are using sed to delete
# any line that contains the string 'CURLOPT_PROXY' or 'CURLOPT_PROXYTYPE'
# from the file 'config.php' defined on top of 'attributes.sh'
if [[ -z "${CURLOPT_PROXY}" || "${CURLOPT_PROXY_ENABLED}" = false ]]; then
    sed -i "/CURLOPT_PROXY/d" ${CONFIG_PHP_TEMPLATE};
    sed -i "/CURLOPT_PROXYTYPE/d" ${CONFIG_PHP_TEMPLATE};
fi
