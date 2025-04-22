# Integração com Gateway de Pagamento — PAGCOMPLETO

Este repositório contém a solução para o teste prático de integração com o gateway de pagamento **PAGCOMPLETO**. O objetivo é implementar o processamento de pagamentos via API, conforme os critérios técnicos fornecidos no material anexo.

## 🧾 Contexto

Realizar a integração com a API do gateway PAGCOMPLETO, implementando o processamento de pedidos e transações conforme as instruções técnicas.

## 🚀 Tecnologias Utilizadas

- PHP
- Laravel 12.9.2
- Docker + Docker Compose
- MySQL

## 🛠️ Como Executar o Projeto

### Pré-requisitos

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

### Passos para rodar localmente

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/seu-repositorio.git
   cd seu-repositorio
   ```

2. Suba os containers:
   ```bash
   docker compose up -d
   ```
3. Execute o comando para processar os pagamentos:
   ```bash
   docker-compose exec app php artisan app:processar-pagamentos
   ```
---
