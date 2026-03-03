# OPTCS Frontend - React + Vite (Railway-compatible)

# Stage 1: Build
FROM node:18-alpine AS build

WORKDIR /app

# Copy package files
COPY package.json package-lock.json ./

# Install dependencies
RUN npm ci

# Copy source code
COPY . .

# Build args for environment variables (passed at build time by Railway)
ARG VITE_API_URL
ARG VITE_BASE_URL
ARG VITE_IMG_URL
ARG VITE_GOOGLE_CLIENT_ID

# Create .env file from build args
RUN echo "VITE_API_URL=${VITE_API_URL}" > .env \
    && echo "VITE_BASE_URL=${VITE_BASE_URL}" >> .env \
    && echo "VITE_IMG_URL=${VITE_IMG_URL}" >> .env \
    && echo "VITE_GOOGLE_CLIENT_ID=${VITE_GOOGLE_CLIENT_ID}" >> .env

# Build production
RUN npm run build

# Stage 2: Serve with nginx
FROM nginx:alpine

# Copy built files
COPY --from=build /app/dist /usr/share/nginx/html

# Copy nginx config template (uses $PORT substitution)
COPY nginx-frontend.conf /etc/nginx/templates/default.conf.template

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
