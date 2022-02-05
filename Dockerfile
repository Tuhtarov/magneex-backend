FROM centrifugo/centrifugo:v3.0.0

RUN apk update && apk add bash

COPY ./docker/dev/centrifugo-config.json /centrifugo

CMD ["centrifugo", "--config=centrifugo-config.json"]

EXPOSE 3000:80

