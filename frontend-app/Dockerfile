FROM node:20-alpine

WORKDIR /app

# Copiar arquivos de dependências
COPY package*.json ./

# Instalar dependências
RUN npm install

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host"]

