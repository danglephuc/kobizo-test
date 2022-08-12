FROM node:lts AS development
# Set working directory
WORKDIR /app
#
COPY package.json /app/package.json
COPY package-lock.json /app/package-lock.json
# Same as npm install
RUN npm install --legacy-peer-deps
RUN npm install -g react-scripts
COPY . /app
ENV PORT=8200
CMD [ "npm", "start" ]
FROM development AS build
RUN npm run build
