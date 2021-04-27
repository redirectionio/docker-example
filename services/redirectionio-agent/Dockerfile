FROM alpine:3.12 as alpine

WORKDIR /tmp

RUN apk add --no-cache wget ca-certificates
RUN wget https://packages.redirection.io/dist/stable/2/any/redirectionio-agent-latest_any_amd64.tar.gz
RUN tar -xzvf redirectionio-agent-latest_any_amd64.tar.gz && mkdir -p /tmp/var/lib/redirectionio

FROM scratch

# Binary copied from tar
COPY --from=alpine /tmp/redirection-agent/redirectionio-agent .

# Configuration, can be replaced by your own
COPY etc /etc

# Root SSL Certificates, needed as we do HTTPS requests to our service
COPY --from=alpine /etc/ssl/certs/ca-certificates.crt /etc/ssl/certs/

# Cache storage for rules
VOLUME /var/lib/redirectionio

CMD ["/redirectionio-agent"]
