FROM node:20-alpine

WORKDIR /app

# Instalar dependências (será sobrescrito pelo volume em dev)
RUN npm install || true

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host"]

