import logging

logging.basicConfig(filemode='w', filename='dev.log', level=logging.INFO, format="%(asctime)s %(name)s %(levelname)s %(message)s")
logger = logging.getLogger("--Kite-Login-reqeust-token--")