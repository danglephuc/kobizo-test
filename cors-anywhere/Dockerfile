FROM node
ENV NPM_CONFIG_LOGLEVEL warn
EXPOSE 8080

# App setup
USER node
ENV HOME=/home/node

WORKDIR $HOME

ENV PATH $HOME/app/node_modules/.bin:$PATH

ADD package.json $HOME
RUN NODE_ENV=production npm install

CMD ["node", "./index.js"]
