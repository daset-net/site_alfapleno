# ============================================================
#  EDUALFA — Site institucional
#  Servidor: OpenLiteSpeed + LSPHP (super rápido)
#  Imagem oficial: litespeedtech/openlitespeed
# ============================================================
FROM litespeedtech/openlitespeed:latest

LABEL org.opencontainers.image.title="EDUALFA" \
      org.opencontainers.image.description="Site institucional EDUALFA sobre OpenLiteSpeed + PHP" \
      maintainer="daset-net"

ENV TZ=America/Sao_Paulo \
    DEBIAN_FRONTEND=noninteractive

# Docroot padrão do vhost "localhost" na imagem oficial
ENV DOCROOT=/var/www/vhosts/localhost/html

# ---- Aplicação -------------------------------------------------------------
# Limpa o conteúdo de exemplo e copia o site
RUN rm -rf ${DOCROOT} && mkdir -p ${DOCROOT}
COPY public/ ${DOCROOT}/

# Pasta de dados persistente (leads do formulário)
RUN mkdir -p /var/www/vhosts/localhost/data \
    && chown -R lsadm:lsadm /var/www/vhosts/localhost \
    && chmod -R 775 /var/www/vhosts/localhost/data

# ---- Rede ------------------------------------------------------------------
# 80  -> HTTP do site      | 7080 -> painel admin do OpenLiteSpeed
EXPOSE 80 7080

# O ENTRYPOINT/CMD já vêm da imagem oficial (inicia o OpenLiteSpeed em foreground)
