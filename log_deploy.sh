rsync -vzrtopg --delete --exclude *.log --exclude *.sql  --exclude=site_data/*  --exclude=store_log/*  --exclude=log/* /data/log_deploy/ 10.32.11.158::www
rsync -vzrtopg --delete --exclude *.log --exclude *.sql --exclude=site_data/*  --exclude=store_log/*  --exclude=log/* /data/log_deploy/ 10.32.42.4::www
rsync -vzrtopg --delete --exclude *.log --exclude *.sql --exclude=site_data/*  --exclude=store_log/*  --exclude=log/* /data/log_deploy/ 10.32.42.31::www
rsync -vzrtopg --delete --exclude *.log --exclude *.sql --exclude=site_data/*  --exclude=store_log/*  --exclude=log/* /data/log_deploy/ 10.32.42.19::www
rsync -vzrtopg --delete --exclude *.log --exclude *.sql --exclude=site_data/*  --exclude=store_log/*  --exclude=log/* /data/log_deploy/ 10.32.42.17::www