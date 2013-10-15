from django.db import models

# Create your models here.

class s_info(models.Model):
	s_datetime = models.CharField(max_length=40)
	s_market = models.CharField(max_length=20)
	s_price = models.CharField(max_length=20)
	s_daily_change = models.CharField(max_length=10)
	s_percent_change = models.CharField(max_length=10)

	def __unicode__(self):
		return self.s_price

