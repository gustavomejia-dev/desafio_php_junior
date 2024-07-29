import mysql.connector
import pandas as pd
import os
from datetime import datetime

# Função para extrair dados e criar um DataFrame
def fetch_data(query, connection):
    """Executa a consulta SQL e retorna um DataFrame com os resultados."""
    return pd.read_sql_query(query, connection)

# Função para criar o relatório Excel
def create_excel_report(db_config, output_file):
    """Cria um relatório Excel com dados das tabelas especificadas na configuração do banco de dados."""
    try:
        # Conectar ao banco de dados MySQL
        conn = mysql.connector.connect(**db_config)

        # Consultas SQL para extrair dados das tabelas
        queries = {
            "Reserva_detalhes": """
                SELECT
                    res.id AS reserva_id,
                    u.name AS 'Nome do Usuario',
                    ro.name AS 'Nome da Sala',
                    res.start_time as 'Inicio',
                    res.end_time as 'Fim'
                FROM
                    reservations res
                INNER JOIN
                    users u ON res.user_id = u.id
                INNER JOIN
                    rooms ro ON res.room_id = ro.id;
            """,
            "Sala_Mais_Reservada": """
                SELECT
                    ro.name as 'Nome Sala',
                    COUNT(*) AS 'Qtd'
                FROM
                    reservations res
                INNER JOIN
                    rooms ro ON res.room_id = ro.id
                GROUP BY
                    ro.name
                ORDER BY
                    Qtd DESC
                LIMIT 1;
            """,
            "Sala_menos_Reservada": """
                SELECT
                    ro.name as 'Nome Sala',
                    COUNT(*) AS 'Qtd'
                FROM
                    reservations res
                INNER JOIN
                    rooms ro ON res.room_id = ro.id
                GROUP BY
                    ro.name
                ORDER BY
                    Qtd ASC
                LIMIT 1;
            """,
            "Sala_reservada_mais_tempo": """
                SELECT
                    start_time as 'Inicio',
                    COUNT(*) AS 'Qtd'
                FROM
                    reservations
                GROUP BY
                    start_time
                ORDER BY
                    'Qtd' DESC
                LIMIT 1;
            """
        }

        # Criar um writer do Excel
        with pd.ExcelWriter(output_file, engine='openpyxl') as writer:
            for sheet_name, query in queries.items():
                # Extrair dados e criar DataFrame
                df = fetch_data(query, conn)
                # Escrever DataFrame no arquivo Excel
                df.to_excel(writer, sheet_name=sheet_name, index=False)

        print(f'Relatório gerado com sucesso em {output_file}')
    
    except mysql.connector.Error as err:
        print(f"Erro ao conectar ao MySQL: {err}")
    
    finally:
        # Fechar a conexão, se estiver aberta
        if 'conn' in locals() and conn.is_connected():
            conn.close()

# Função para verificar e criar o diretório, se necessário
def ensure_directory_exists(directory):
    """Verifica se o diretório existe; se não, cria."""
    if not os.path.exists(directory):
        os.makedirs(directory)

# Função para gerar o nome do arquivo com data e hora
def generate_report_filename(directory):
    """Gera um nome de arquivo com base na data e hora atuais."""
    now = datetime.now()
    formatted_date = now.strftime('%Y-%m-%d_%H-%M-%S')
    return os.path.join(directory, f'relatorios_{formatted_date}.xlsx')

# Configuração de conexão para MySQL
db_config = {
    'user': 'gustavo_mejia',
    'password': 'DesafioAvant@2024',
    'host': 'desafio-tecnico.cf1afo0ns4vr.us-west-2.rds.amazonaws.com',
    'database': 'gustavo_mejia'
}

# Caminho para a pasta onde o relatório será salvo
report_directory = 'relatorio'
output_excel_file = generate_report_filename(report_directory)

# Garantir que a pasta existe
ensure_directory_exists(report_directory)

# Criar o relatório
create_excel_report(db_config, output_excel_file)