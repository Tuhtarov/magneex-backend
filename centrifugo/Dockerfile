FROM centrifugo/centrifugo:v3.0.0

RUN apk update && apk add bash

COPY centrifugo-config.json /centrifugo

EXPOSE 3000:3000

CMD ["centrifugo", "--config=centrifugo-config.json", "--port=3000"]

