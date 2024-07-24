FROM alpine:3.19 AS alpine

WORKDIR /tmp

RUN apk add --no-cache wget ca-certificates \
    && wget https://packages.redirection.io/dist/stable/2/any/redirectionio-agent-latest_any_amd64.tar.gz \
    && tar -xzvf redirectionio-agent-latest_any_amd64.tar.gz

FROM scratch

# Binary copied from tar
COPY --from=alpine /tmp/redirection-agent/redirectionio-agent /usr/local/bin/redirectionio-agent

# Configuration, can be replaced by your own
COPY etc /etc

# Root SSL Certificates, needed as we do HTTPS requests to our service
COPY --from=alpine /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/

# Cache storage for rules
VOLUME /var/lib/redirectionio

CMD ["/usr/local/bin/redirectionio-agent"]
