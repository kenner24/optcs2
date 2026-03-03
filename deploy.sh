#!/bin/bash
# =============================================
# OPTCS One-Command Deploy Script
# =============================================
# Prerequisites: Docker and Docker Compose installed
#
# Usage:
#   1. Fill in .env.production with your values
#   2. Run: chmod +x deploy.sh && ./deploy.sh
# =============================================

set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

echo ""
echo "========================================="
echo "  OPTCS Deployment"
echo "========================================="
echo ""

# Check prerequisites
command -v docker >/dev/null 2>&1 || { echo "ERROR: Docker is not installed. Install it from https://docs.docker.com/get-docker/"; exit 1; }
command -v docker compose >/dev/null 2>&1 || command -v docker-compose >/dev/null 2>&1 || { echo "ERROR: Docker Compose is not installed."; exit 1; }

# Check .env file
if [ ! -f "$SCRIPT_DIR/.env.production" ]; then
    echo "ERROR: .env.production not found in deployment folder."
    echo "Copy .env.production.example and fill in your values."
    exit 1
fi

# Check for placeholder values
if grep -q "CHANGE_THIS" "$SCRIPT_DIR/.env.production"; then
    echo "WARNING: .env.production still contains placeholder values."
    echo "Please update all CHANGE_THIS values before deploying."
    read -p "Continue anyway? (y/N): " confirm
    [ "$confirm" = "y" ] || exit 1
fi

# Load environment
export $(grep -v '^#' "$SCRIPT_DIR/.env.production" | xargs)

echo "1/4 - Building containers..."
cd "$SCRIPT_DIR"
docker compose -f docker-compose.yml --env-file .env.production build

echo ""
echo "2/4 - Starting database..."
docker compose -f docker-compose.yml --env-file .env.production up -d db
echo "     Waiting for database to be ready..."
sleep 15

echo ""
echo "3/4 - Starting backend API..."
docker compose -f docker-compose.yml --env-file .env.production up -d backend
echo "     Waiting for migrations to complete..."
sleep 10

echo ""
echo "4/4 - Starting frontend..."
docker compose -f docker-compose.yml --env-file .env.production up -d frontend

echo ""
echo "========================================="
echo "  OPTCS is now running!"
echo "========================================="
echo ""
echo "  Frontend:  ${FRONTEND_URL:-http://localhost}"
echo "  Backend:   ${APP_URL:-http://localhost:8000}"
echo ""
echo "  To view logs:    docker compose -f $SCRIPT_DIR/docker-compose.yml logs -f"
echo "  To stop:         docker compose -f $SCRIPT_DIR/docker-compose.yml down"
echo "  To restart:      docker compose -f $SCRIPT_DIR/docker-compose.yml restart"
echo ""
