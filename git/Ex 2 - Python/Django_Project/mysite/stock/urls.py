from django.conf.urls import patterns, url

from stock import views

urlpatterns = patterns('',
    url(r'^$', views.index, name='index'),
	url(r'^app/stock-news',views.stocknews, name='stock-news'),
)
