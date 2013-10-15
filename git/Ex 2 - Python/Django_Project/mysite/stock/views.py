# Create your views here.

from django.shortcuts import render
from django.template import Context 
from django.http import HttpResponse
from stock.models import s_info
from bs4 import BeautifulSoup
import requests, datetime

def index(request):
	
	return render(request,'stock/index.html')

def updateDB(one,two,three,four,five,six):

	stock = s_info(s_datetime = str(datetime.datetime.now()),
			       s_market = 'BSE',
				   s_price = one,
				   s_daily_change = two,
				   s_percent_change = three,
	               )
	stock.save()

	stock = s_info(s_datetime = str(datetime.datetime.now()),
				   s_market = 'NSE',
				   s_price = four,
				   s_daily_change = five,
				   s_percent_change = six,
	               )
	stock.save()	



def generateContext(soup):

	bse = soup.find(id="sensTab1")
	bse_span = bse.contents[-2]
	
	nse = soup.find(id="sensTab2")
	nse_span = nse.contents[-2]

	updateDB(str(bse.div.span.contents[0]),str(bse_span.contents[0]),str(bse.contents[-1]),
			 str(nse.div.span.contents[0]),str(nse_span.contents[0]),str(nse.contents[-1]))

	stock_context = Context({ 'bse_index_value': bse.div.span.contents[0],
							  'bse_points_change': bse_span.contents[0],
							  'bse_change_percentage': bse.contents[-1],  
							  'nse_index_value': nse.div.span.contents[0],
							  'nse_points_change': nse_span.contents[0],
							  'nse_change_percentage': nse.contents[-1]  
		})

	return stock_context


def stocknews(request):

	
	var = requests.get("http://money.rediff.com/")
	soup = BeautifulSoup(var.text)
	
	stock_context = generateContext(soup)

	return render(request, 'stock/stock-news.html', stock_context)
	



